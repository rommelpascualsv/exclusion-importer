<?php
namespace App\Services;

use App\Events\FileImportFailed;
use App\Events\FileImportSuccessful;
use App\Events\FileUpdateFailed;
use App\Events\FileUpdateSuccessful;
use App\Import\Lists\ExclusionList;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Repositories\ExclusionListFileRepository;
use App\Repositories\ExclusionListRecordRepository;
use App\Repositories\ExclusionListRepository;
use App\Repositories\ExclusionListVersionRepository;
use App\Repositories\Query;
use App\Response\JsonResponse;
use App\Services\Contracts\ImportFileServiceInterface;
use App\Utils\ExceptionUtils;
use App\Utils\FileUtils;

/**
 * Service class that handles the import related processes.
 *
 */
class ImportFileService implements ImportFileServiceInterface
{
    use JsonResponse;
    
    private $exclusionListDownloader;
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListRecordRepo;
    private $exclusionListVersionRepo;
    
    public function __construct(ExclusionListHttpDownloader $exclusionListHttpDownloader = null, 
            ExclusionListRepository $exclusionListRepo = null, 
            ExclusionListFileRepository $exclusionListFilesRepo = null,
            ExclusionListRecordRepository $exclusionListRecordRepo = null,
            ExclusionListVersionRepository $exclusionListVersionRepo = null)
    {
        $this->exclusionListDownloader = $exclusionListHttpDownloader ? $exclusionListHttpDownloader : new ExclusionListHttpDownloader();
        $this->exclusionListRepo = $exclusionListRepo ? $exclusionListRepo : new ExclusionListRepository();
        $this->exclusionListFileRepo = $exclusionListFilesRepo ? $exclusionListFilesRepo : new ExclusionListFileRepository();
        $this->exclusionListRecordRepo = $exclusionListRecordRepo ? $exclusionListRecordRepo : new ExclusionListRecordRepository();
        $this->exclusionListVersionRepo = $exclusionListVersionRepo ? $exclusionListVersionRepo : new ExclusionListVersionRepository();
    }
    
    /**
     * Retrieves a list of active states to show in the import page.
     *
     * @return array
     */ 
    public function getExclusionList()
    {
        $activeExclusionLists = $this->exclusionListRepo->query(['is_active' => 1]);
        
        $collection = [];
        foreach ($activeExclusionLists as $activeExclusionList) {
            $prefix = $activeExclusionList->prefix;
            $activeExclusionList->update_required = ! $this->isExclusionListUpToDate($prefix, $this->getLatestFileHash($prefix));
            $collection[$prefix] = json_decode(json_encode($activeExclusionList), true);
        }
        return $collection;
    }
    
    /**
     * Imports the downloaded file to database
     * @param $url The url of the exclusion list to import
     * @param $prefix The state prefix
     * @param $shouldSaveFile boolean to determine if the record needs to be saved in files table
     *
     * @return object The object containing the result of the operation
     */
    public function importFile($url, $prefix)
    {
        $exclusionListFiles = null;
        
        try {
            
            info('Trying to import file for ' . $prefix);
            
            if (! trim($url)) {
                return $this->createResponse('No URL was specified for : ' . $prefix, false);
            }
            
            // Create ExclusionList class
            $exclusionList = $this->createExclusionList($prefix);
            
            // Update the uri of the exclusion list
            $this->updateUrl($exclusionList, $url);
            
            $hash = null;
            
            if ($this->isExclusionListDownloadable($exclusionList)) {
                
                // Download the raw exclusion list file(s) to a local folder
                $exclusionListFiles = $this->downloadExclusionListFiles($exclusionList);
                
                // Update the files table with the downloaded file content and its corresponding hash
                $hash = $this->updateFiles($exclusionListFiles, $prefix);
                
                if ($hash && $this->isExclusionListUpToDate($prefix, $hash) && ! $this->isExclusionListRecordsEmpty($prefix)) {
                    info($prefix . ": State is already up-to-date.");
                    return $this->createResponse('State is already up-to-date.', true);
                }
                
                if ($exclusionList->type !== 'html') {
                    // Set the ExclusionList's uri to the path of the locally downloaded file(s)
                    // so downstream processes would just work with the local copies
                    $exclusionList->uri = implode(',', $exclusionListFiles);
                }
            }
            
            info('Starting exclusion list import for ' . $prefix);

            $exclusionList->retrieveData();
                
            $this->createListProcessor($exclusionList)->insertRecords();
            
            if ($hash) {
                $this->updateImportedVersionTo($hash, $prefix);
            }
            
            event('file.import.successful', new FileImportSuccessful($this->now(), 'Successfully imported exclusion list', $prefix));
            
            info('Finished exclusion list import for ' . $prefix);
            
            return $this->createResponse('', true);
            
        } catch (\Exception $e) {
            
            info('Encountered an error while trying to import exclusion list for ' . $prefix . ' : ' . $e->getMessage());

            $errorMessage = $this->getErrorMessageFrom($e);
            
            event('file.import.failed', new FileImportFailed($this->now(), 'Failed to import exclusion list : ' . $errorMessage, $prefix));
            
            return $this->createResponse($errorMessage, false);
            
        } finally {
            
            FileUtils::deleteIfInDir($this->exclusionListDownloader->getDownloadDirectory(), $exclusionListFiles);
        }
    }

    /**
     * Refreshes the records whenever there are changes found in the import file.
     *
     * {@inheritDoc}
     * @see \App\Services\Contracts\FileService::refreshRecords()
     */
    public function refreshRecords()
    {
        $exclusionLists = $this->exclusionListRepo->query();
    
        foreach ($exclusionLists as $exclusionList) {
    
            $prefix = $exclusionList->prefix;
            $importUrl = $this->getUrl($prefix);
            $isAutoImport = ($exclusionList->is_auto_import == 1);
            
            $exclusionListFiles = null;
    
            try {
    
                info('Refreshing records for ' . $prefix);
               
                $exclusionList = $this->createExclusionList($prefix);
                
                $this->updateUrl($exclusionList, $importUrl, true); //So that those with auto-generated URIs have a chance to crawl for their URIs
                
                $importUrl = trim($exclusionList->uri);
    
                if (empty($importUrl)) {
                    info('Import url for ' . $prefix . ' is null or empty. Skipping refresh of this exclusion list');
                    continue;
                }
                
                if ($isAutoImport) {
    
                    //pass false to indicate that there's no need to save the blob to the files table as it was already saved before this
                    info('Performing auto-import for ' . $prefix);
    
                    $this->importFile($importUrl, $prefix);
    
                    info('Auto-import complete for ' . $prefix);
    
                } else {
    
                    if (! $this->isExclusionListDownloadable($exclusionList)) {
                        info('\''. $prefix . '\' is not configured for auto import and whose list type is not downloadable. Skipping to next exclusion list...');
                        continue;
                    }
                        
                    info('\''. $prefix . '\' is not configured for auto import. Updating files...');
                    
                    $exclusionListFiles = $this->exclusionListDownloader->downloadFiles($exclusionList);

                    //If the list is not configured for auto-import, just update its file repository copy to the latest
                    $this->updateFiles($exclusionListFiles, $prefix);
                    
                    event('file.update.successful', new FileUpdateSuccessful($this->now(), 'Successfully updated exclusion list file', $prefix));
                }
    
            } catch (\Exception $e) {

                $errorMessage = 'An error occurred while trying to refresh ' . $prefix . ' with url ' . $importUrl . ' : ' . $e->getMessage();
                
                event('file.update.failed', new FileUpdateFailed($this->now(), 'Failed to update exclusion list file : '. $this->getErrorMessageFrom($e), $prefix));
                
                info($errorMessage);
    
            } finally {

                FileUtils::deleteIfInDir($this->exclusionListDownloader->getDownloadDirectory(), $exclusionListFiles);
            }
        }
    }    
    
    /**
     * Retrieves the corresponding list processor based on the passed object.
     * @param object $listObject the exclusion list object
     * @return object the list processor object
     */
    protected function createListProcessor(ExclusionList $listObject)
    {
        return new ListProcessor($listObject);
    }
    
    /**
     * Returns the corresponding exclusion list object for a given state prefix.
     *
     * @param string $listPrefix the state prefix
     *
     * @return object The state-specific exclusion list object
     */
    protected function createExclusionList($listPrefix)
    {
        $listFactory = new ListFactory();
        return $listFactory->make($listPrefix);
    }
    
    private function getLatestFileHash($prefix)
    {
        $files = Query::create()
                 ->setTable('files')
                 ->addCriteria(['state_prefix' => $prefix])
                 ->setOrderBy('date_last_downloaded')
                 ->setOrderByDirection('desc')
                 ->execute();
        
        if (! $files) {
            return null;    
        }
        
        return $files[0]->hash;
    }
    
    /**
     * Updates the uri of the exclusion list with the given uri
     *
     * @param ExclusionList $exclusionList
     * @param string $url the url to update to
     * @param boolean skipRepoUpdate true to skip updating the exclusion_lists
     * table with the url, defaults to false
     */
    private function updateUrl(ExclusionList $exclusionList, $url, $skipRepoUpdate = false)
    {
        $prefix = $exclusionList->dbPrefix;
        
        if (! $exclusionList->isUriAutoGenerated) {
            $exclusionList->uri = $url ? htmlspecialchars_decode($url) : $this->getUrl($prefix);
        }
        
        if (! $skipRepoUpdate) {
            $this->exclusionListRepo->update($prefix, ['import_url' => $exclusionList->uri]);
        }
    }
    
    private function getUrl($prefix)
    {
        $record = $this->exclusionListRepo->find($prefix);     
        return $record ? $record[0]->import_url : '';
    }
    
    private function isExclusionListDownloadable(ExclusionList $exclusionList)
    {
        return $this->exclusionListDownloader->supports($exclusionList->type);
    }
    
    /**
     * Downloads the exclusion list files from the sources indicated in $exclusionList->uri
     * to a local folder. Returns an array containing the file paths to the downloaded files, 
     * null if there are no downloaded files, or false if an error occurs while 
     * trying to download the files
     * @param ExclusionList $exclusionList
     * @return mixed array/boolean
     */
    private function downloadExclusionListFiles(ExclusionList $exclusionList)
    {
        return $this->exclusionListDownloader->downloadFiles($exclusionList);
    }
    
    /**
     * Updates the files repository with the latest file content and its corresponding
     * hash, if applicable
     * @param exclusionListFiles
     */
    private function updateFiles($exclusionListFiles, $prefix)
    {
        $versionFile = null;
    
        try {
    
            // If we have multiple files, zip them up into a single archive
            $versionFile = $this->consolidateFiles($exclusionListFiles, $prefix);
    
            // Determine the version type (pdf, xls, html, etc). If multiple files
            // were downloaded, it's automatically 'zip' since we consolidated
            // the files into a single zip archive above. Otherwise, we get the
            // type from the file extension of the first element
            $versionFileType = count($exclusionListFiles) > 1 ? 'zip' : pathinfo($exclusionListFiles[0], PATHINFO_EXTENSION);
    
            $hash = $this->createAndSaveFileHash($versionFile, $versionFileType, $prefix);
    
            // Insert the file contents into the files repository
            $this->saveFileContents($versionFile, $hash, $prefix);
    
            return $hash;
    
        } finally {
    
            FileUtils::deleteIfInDir(sys_get_temp_dir(), $versionFile);
        }
    }    
    
    /**
     * Generates a single zip archive of the files contained by $exclusionListFiles
     * if there are multiple files specified by $exclusionListFiles and returns
     * the path to the zip archive. Otherwise, if there is only element contained
     * by $exclusionListFiles, returns the value of that element
     * @param array $files list of file paths
     * @throws \Exception
     * @return string the path to the archive containing the files specified by
     * $exclusionListFiles if there are multiple files specified in $exclusionListFiles,
     * otherwise just returns the value of the first element
     */
    private function consolidateFiles($files, $prefix)
    {
        if (! $files) {
            return null;
        }
        
        $result = null;
        
        if (count($files) > 1) {
        
            $result = tempnam(sys_get_temp_dir(), $prefix);

            $zipped = FileUtils::createZip($files, $result, true);
        
            if (! $zipped) {
                throw new \Exception('An error occurred while creating the archive of exclusion list files for ' . $prefix);
            }
        
        } else {
            $result = $files[0];
        }

        return $result;
    }
    
    
    /**
     * Creates a hash of the file and saves it in the files repository
     * 
     * @param string $file the path to the file whose hash will be generated and
     * saved in the file repository
     * @param string $prefix the exclusion list prefix
     * @throws Exception
     */
    private function createAndSaveFileHash($file, $fileType, $prefix)
    {
        if (! $file) {
            return;
        }
        
        $hash = hash_file('sha256', $file);
        
        $record = [
            'state_prefix' => $prefix,
            'hash' => $hash,
            'img_type' => $fileType
        ];
        
        // Insert a new record in the files repository if it does not yet contain
        // a hash for the given file prefix and file index, otherwise we don't need
        // to insert it in the files repository if a hash already exists
        if (! $this->exclusionListFileRepo->contains($record)) {
            
            $record['date_last_downloaded'] = $this->now();
            $this->exclusionListFileRepo->create($record);
        }
    
        return $hash;
    }
    
    private function saveFileContents($file, $hash, $prefix)
    {
        if (! $file) {
            return;
        }
       
        $repositoryFile = null;
        
        try {

            $record = [
                'state_prefix' => $prefix,
                'hash' => $hash
            ];
            
            $existing = Query::create()->setTable('files')->addCriteria($record)->execute();
            
            if (! $existing) {
                throw new \Exception('Illegal state encountered : No existing record in files repository was found to update with file contents');
            }
            
            // Load the contents of the file stored in the repository in a temp file and compare that with the downloaded file
            $repositoryFile = tempnam(sys_get_temp_dir(), $prefix);
            
            file_put_contents($repositoryFile, $existing[0]->img_data, LOCK_EX);
            
            if (! FileUtils::contentEquals($repositoryFile, $file)) {
                $this->exclusionListFileRepo->update($record, ['img_data' => file_get_contents($file)]);
            }
            
        } finally {
            if ($repositoryFile) unlink($repositoryFile);
        }
    }
    
    private function isExclusionListUpToDate($prefix, $latestHash)
    {
        $records = $this->exclusionListVersionRepo->find($prefix);
        
        return $records && $records[0]->last_imported_hash === $latestHash; 
    }
    
    private function updateImportedVersionTo($hash, $prefix)
    {
        $this->exclusionListVersionRepo->createOrUpdate($prefix, [
            'last_imported_hash' => $hash,
            'last_imported_date' => $this->now()
        ]);
    }
    
    private function isExclusionListRecordsEmpty($prefix)
    {
        return $this->exclusionListRecordRepo->size($prefix) === 0;
    }
    
    private function now()
    {
        return date('Y-m-d H:i:s');        
    }
    
    private function getErrorMessageFrom(\Exception $e)
    {
        return $e instanceof \PDOException ? ExceptionUtils::getJsonSerializableErrorMessage($e) : $e->getMessage();
    }    

}
