<?php
namespace App\Services;

use App\Events\FileDownloadFailed;
use App\Events\FileDownloadSucceeded;
use App\Events\FileParseFailed;
use App\Events\FileParseSucceeded;
use App\Events\FileUpdateFailed;
use App\Events\FileUpdateSucceeded;
use App\Events\SaveRecordsFailed;
use App\Events\SaveRecordsSucceeded;
use App\Import\Lists\ExclusionList;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Repositories\ExclusionListFileRepository;
use App\Repositories\ExclusionListRecordRepository;
use App\Repositories\ExclusionListRepository;
use App\Repositories\ExclusionListVersionRepository;
use App\Repositories\GetActiveExclusionListsQuery;
use App\Repositories\GetAllExclusionListsQuery;
use App\Repositories\GetFilesForPrefixAndHashQuery;
use App\Repositories\GetFilesForPrefixQuery;
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
    
    const FILE_HASH_ALGO = 'sha256';
    
    private $exclusionListDownloader;
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListRecordRepo;
    private $exclusionListVersionRepo;
    private $getActiveExclusionListsQuery;
    private $getAllExclusionListsQuery;
    private $getFilesForPrefixAndHashQuery;
    private $getFilesForPrefixQuery;
    
    public function __construct(ExclusionListHttpDownloader $exclusionListHttpDownloader = null, 
            ExclusionListRepository $exclusionListRepo = null, 
            ExclusionListFileRepository $exclusionListFilesRepo = null,
            ExclusionListRecordRepository $exclusionListRecordRepo = null,
            ExclusionListVersionRepository $exclusionListVersionRepo = null,
            GetActiveExclusionListsQuery $getActiveExclusionListsQuery = null,
            GetAllExclusionListsQuery $getAllExclusionListsQuery = null,
            GetFilesForPrefixAndHashQuery $getFilesForPrefixAndHashQuery = null,
            GetFilesForPrefixQuery $getFilesForPrefixQuery = null)
    {
        $this->exclusionListDownloader = $exclusionListHttpDownloader ? $exclusionListHttpDownloader : new ExclusionListHttpDownloader();
        $this->exclusionListRepo = $exclusionListRepo ? $exclusionListRepo : new ExclusionListRepository();
        $this->exclusionListFileRepo = $exclusionListFilesRepo ? $exclusionListFilesRepo : new ExclusionListFileRepository();
        $this->exclusionListRecordRepo = $exclusionListRecordRepo ? $exclusionListRecordRepo : new ExclusionListRecordRepository();
        $this->exclusionListVersionRepo = $exclusionListVersionRepo ? $exclusionListVersionRepo : new ExclusionListVersionRepository();
        $this->getActiveExclusionListsQuery = $getActiveExclusionListsQuery ? $getActiveExclusionListsQuery : new GetActiveExclusionListsQuery(); 
        $this->getAllExclusionListsQuery = $getAllExclusionListsQuery ? $getAllExclusionListsQuery : new GetAllExclusionListsQuery();
        $this->getFilesForPrefixAndHashQuery = $getFilesForPrefixAndHashQuery ? $getFilesForPrefixAndHashQuery : new GetFilesForPrefixAndHashQuery();
        $this->getFilesForPrefixQuery = $getFilesForPrefixQuery ? $getFilesForPrefixQuery : new GetFilesForPrefixQuery();
    }
    
    /**
     * Retrieves a list of active states to show in the import page.
     *
     * @return array
     */ 
    public function getExclusionList()
    {
        $activeExclusionLists = $this->getActiveExclusionListsQuery->execute();
        
        $collection = [];
        
        foreach ($activeExclusionLists as $activeExclusionList) {
            
            $prefix = $activeExclusionList->prefix;
            
            $latestRepoFile = $this->getLatestRepoFileFor($prefix);
            
            $activeExclusionList->update_required = ! $latestRepoFile || ! $this->isExclusionListUpToDate($prefix, $latestRepoFile->hash);
            
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
                
                if ($exclusionList->type) {
                    // Set the ExclusionList's uri to the path of the locally downloaded file(s)
                    // so downstream processes would just work with the local copies
                    $exclusionList->uri = implode(',', $exclusionListFiles);
                }
            }
            
            info('Starting exclusion list import for ' . $prefix);

            $this->parseRecords($exclusionList);
                
            $this->saveRecords($exclusionList);
            
            if ($hash) {
                $this->updateImportedVersionTo($hash, $prefix);
            }
            
            info('Finished exclusion list import for ' . $prefix);
            
            return $this->createResponse('', true);
            
        } catch (\Exception $e) {
            
            info('Encountered an error while trying to import exclusion list for ' . $prefix . ' : ' . $e->getMessage());

            $errorMessage = $this->getErrorMessageFrom($e);
                        
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
        $exclusionLists = $this->getAllExclusionListsQuery->execute();

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
                    
                    $exclusionListFiles = $this->downloadExclusionListFiles($exclusionList);

                    //If the list is not configured for auto-import, just update its file repository copy to the latest
                    $this->updateFiles($exclusionListFiles, $prefix);
                    
                }
    
            } catch (\Exception $e) {

                $errorMessage = 'An error occurred while trying to refresh ' . $prefix . ' with url ' . $importUrl . ' : ' . $e->getMessage();
                error_log($errorMessage);                
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
    
    private function getLatestRepoFileFor($prefix)
    {
        $files = $this->getFilesForPrefixQuery->execute($prefix);
        
        return $files ? $files[0] : null;
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
        $prefix = $exclusionList->dbPrefix;
        
        try {
            
            $files = $this->exclusionListDownloader->downloadFiles($exclusionList);
            
            event('file.download.succeeded', (new FileDownloadSucceeded())->setObjectId($prefix));
            
            return $files;
            
        } catch (\Exception $e) {
            
            event('file.download.failed', (new FileDownloadFailed())->setObjectId($prefix)->setDescription('Failed to download file : ' . $this->getErrorMessageFrom($e)));
            
            throw $e;           
        }
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
    
        } catch(\Exception $e) {

            event('file.update.failed', (new FileUpdateFailed())->setObjectId($prefix)->setDescription('Failed to update file : ' . $this->getErrorMessageFrom($e)));
            throw $e;
            
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
        
        $hash = null;
        
        if ($fileType === 'zip') {
            // For zip files, we cannot rely on the hash of the zip file
            // to determine if we need to insert a new hash in the database (since
            // zip files having the same content can have different hashes
            // due to creation timestamp differences). We first have to retrieve the latest
            // file in the repo and compare its content with the downloaded zip file to 
            // see if their contents are the same. If the contents are the same, 
            // we return the hash of what's already in the database, otherwise we
            // save the hash of the downloaded file in the database
            $latestRepoFile = $this->getLatestRepoFileFor($prefix);
            
            if ($latestRepoFile && $this->contentEquals($latestRepoFile->img_data, $file)) {
                $hash = $latestRepoFile->hash;
            }
        } 
        
        if (! $hash) {
            $hash = hash_file(self::FILE_HASH_ALGO, $file);
        }
        
        $record = [
            'state_prefix' => $prefix,
            'hash' => $hash,
            'img_type' => $fileType
        ];
        
        // Insert a new record in the files repository if it does not yet contain
        // a hash for the given file prefix and file index, otherwise we don't need
        // to insert it in the files repository if a hash already exists
        if (! $this->exclusionListFileRepo->contains($record)) {
            
            info('Inserting new file hash to the files repository : ' . $hash);
            
            $record['date_last_downloaded'] = $this->now();
            
            $this->exclusionListFileRepo->create($record);
            
        } else {
            
            $this->exclusionListFileRepo->update($record, ['date_last_downloaded' => $this->now()]);
            
            info('Existing file hash found in files repository : ' . $hash);
        }
    
        return $hash;
    }
    
    private function saveFileContents($file, $hash, $prefix)
    {
        if (! $file) {
            return;
        }
       
        $record = [
            'state_prefix' => $prefix,
            'hash' => $hash
        ];
        
        $existing = $this->getFilesForPrefixAndHashQuery->execute($prefix, $hash);
        
        if (! $existing) {
            throw new \Exception('Illegal state encountered : No existing record in files repository was found to update with file contents');
        }
        
        if (! $this->contentEquals($existing[0]->img_data, $file)) {
            
            info('Updating file content for hash : ' . $hash);
            
            $this->exclusionListFileRepo->update($record, ['img_data' => file_get_contents($file)]);
            
            event('file.update.successful', (new FileUpdateSucceeded())->setObjectId($prefix));
            
        } else {
            
            info ('Last saved file is already up-to-date for hash : ' . $hash);
            
            event('file.update.successful', (new FileUpdateSucceeded())->setObjectId($prefix)->setDescription('Last saved file is already up-to-date'));
        }

    }
    
    private function parseRecords(ExclusionList $exclusionList)
    {
        $prefix = $exclusionList->dbPrefix;
        
        try {
            
            $exclusionList->retrieveData();
            
            event('file.parse.succeeded', (new FileParseSucceeded())->setObjectId($prefix));
            
        } catch (\Exception $e) {
            
            event('file.parse.failed', (new FileParseFailed())->setObjectId($prefix)->setDescription('Failed to parse file content : ' . $this->getErrorMessageFrom($e)));
            throw $e;
        }
    }
    
    private function saveRecords(ExclusionList $exclusionList)
    {
        $prefix = $exclusionList->dbPrefix;
        
        try {
            
            $this->createListProcessor($exclusionList)->insertRecords();
            
            event('file.saverecords.succeeded', (new SaveRecordsSucceeded())->setObjectId($prefix));
            
        } catch (\Exception $e) {
            
            event('file.saverecords.failed', (new SaveRecordsFailed())->setObjectId($prefix)->setDescription('Failed to save records : ' . $this->getErrorMessageFrom($e)));
            throw $e;
        }
    }    
    
    /**
     * Compares $content with the contents of $file
     * @param string $content the string content to compare with the contents of
     * the file
     * @param string $file the file path
     * @return boolean
     */
    private function contentEquals($content, $file)
    {
        $contentFile = null;
    
        try {
            // Load $contents in a temp file and compare that with $file
            $contentFile = tempnam(sys_get_temp_dir(), str_random(4));
    
            file_put_contents($contentFile, $content, LOCK_EX);
    
            return FileUtils::contentEquals($contentFile, $file);
    
        } finally {
            if ($contentFile) unlink($contentFile);
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
