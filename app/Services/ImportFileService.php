<?php
namespace App\Services;

use App\Import\Lists\ExclusionList;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Response\JsonResponse;
use App\Services\Contracts\ImportFileServiceInterface;
use App\Utils\FileUtils;
use App\Repositories\ExclusionListRepository;
use App\Repositories\ExclusionListFilesRepository;

/**
 * Service class that handles the import related processes.
 *
 */
class ImportFileService implements ImportFileServiceInterface
{
    use JsonResponse;
    
    private $exclusionListDownloader;
    
    private $exclusionListRepo;
    
    private $exclusionListFilesRepo;
    
    public function __construct(ExclusionListHttpDownloader $exclusionListHttpDownloader = null, 
            ExclusionListRepository $exclusionListRepo = null, 
            ExclusionListFilesRepository $exclusionListFilesRepo = null)
    {
        $this->exclusionListDownloader = $exclusionListHttpDownloader ? $exclusionListHttpDownloader : new ExclusionListHttpDownloader();
        $this->exclusionListRepo = $exclusionListRepo ? $exclusionListRepo : new ExclusionListRepository();
        $this->exclusionListFilesRepo = $exclusionListFilesRepo ? $exclusionListFilesRepo : new ExclusionListFilesRepository();
    }
    
    /**
     * Retrieves a list of active states to show in the import page.
     *
     * @return array
     */ 
    public function getExclusionList()
    {
        $activeExclusionLists = $this->exclusionListRepo->get(['is_active' => 1]);
        
        $collection = [];
        foreach ($activeExclusionLists as $activeExclusionList) {
            $collection[$activeExclusionList->prefix] = json_decode(json_encode($activeExclusionList), true);
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
        $latestExclusionListFiles = null;

        try {
            
            info('Trying to import file for ' . $prefix);
            
            if (! trim($url)) {
                return $this->createResponse('No URL was specified for : ' . $prefix, false);
            }
            
            // 1. Create ExclusionList class
            $exclusionList = $this->createExclusionList($prefix);
            
            // 2. Update the uri of the exclusion list
            $this->updateUrl($exclusionList, $url);
            // 3. Download the raw exclusion list file(s) to a local folder
            $latestExclusionListFiles = $this->downloadExclusionListFiles($exclusionList);

            // 4. Update the files stored in the database with the latest downloaded files if the downloaded files
            // are not the same with the database copies
            $this->updateRepositoryFiles($latestExclusionListFiles, $prefix);

            // 5. Checks if state is updateable
            if (! $this->isReadyForUpdate($prefix) && ! $this->isExclusionListRecordsEmpty($prefix)) {
                info($prefix . ": State is already up-to-date.");
                return $this->createResponse('State is already up-to-date.', true);
            }

            info('Starting exclusion list import for ' . $prefix);

            if ($latestExclusionListFiles) {
                // Set the ExclusionList's uri to the path of the locally downloaded file(s)
                // so downstream processes would just work with the local copies
                $exclusionList->uri = implode(',', $latestExclusionListFiles);
            }

            // 6. Retrieves data for a given file type
            $exclusionList->retrieveData();

            // 7. Insert records to state table
            $this->createListProcessor($exclusionList)->insertRecords();
            
            // 8. Since we already updated state records, set ready_for_update to N
            $this->setReadyForUpdate($prefix, 'N');
            
            info('Finished exclusion list import for ' . $prefix);
            
            // 9. Return successful response
            return $this->createResponse('', true);
            
        } catch (\PDOException $e) {
            
            info('Encountered an error while trying to import exclusion list for ' . $prefix . ' : ' . $e->getMessage());
            
            return $this->createResponse($this->getErrorMessageFrom($e), false);
            
        } catch (\Exception $e) {
            
            info('Encountered an error while trying to import exclusion list for ' . $prefix . ' : ' . $e->getMessage());
            
            return $this->createResponse($e->getMessage(), false);
            
        } finally {
            
            $this->deleteQuiety($latestExclusionListFiles);
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
        $exclusionLists = $this->exclusionListRepo->get();
    
        // iterate import urls
        foreach ($exclusionLists as $exclusionList) {
    
            $prefix = $exclusionList->prefix;
            $import_url = trim($this->getUrl($prefix));
            $isAutoImport = ($exclusionList->is_auto_import == 1);
            
            $latestExclusionListFiles = null;
    
            try {
    
                info('Refreshing records for ' . $prefix);
               
                $exclusionList = $this->createExclusionList($prefix);
                
                $this->updateUrl($exclusionList, $import_url); //So that those with auto-generated URIs have a chance to crawl for their URIs
                
                $import_url = trim($exclusionList->uri);
    
                if (empty($import_url)) {
                    info('Import url for ' . $prefix . ' is null or empty. Skipping refresh of this exclusion list');
                    continue;
                }
                
                if ($isAutoImport) {
    
                    //pass false to indicate that there's no need to save the blob to the files table as it was already saved before this
                    info('Performing auto-import for ' . $prefix);
    
                    $this->importFile($import_url, $prefix);
    
                    info('Auto-import complete for ' . $prefix);
    
                } else {
    
                    info('\''. $prefix . '\' is not configured for auto import. Updating file repository...');
                    
                    $latestExclusionListFiles = $this->exclusionListDownloader->downloadFiles($exclusionList);

                    //If the list is not auto-importable, just update its file repository copy to the latest
                    $this->updateRepositoryFiles($latestExclusionListFiles, $prefix);
                }
    
            } catch (\Exception $e) {
                
                $errorMessage = 'An error occurred while trying to refresh ' . $prefix . ' with url ' . $import_url . ' : ' . $e->getMessage();
                error_log($errorMessage);
                info($errorMessage);
    
            } finally {

                $this->deleteQuiety($latestExclusionListFiles);
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
    
    /**
     * Updates the uri of the exclusion list with the given uri
     *
     * @param ExclusionList $exclusionList
     * @param string $url the url to update to
     * @param boolean skipRepoUpdate true to skip updating the exclusion_lists
     * table with the url, defaults to false
     */
    private function updateUrl(ExclusionList $exclusionList, $url)
    {
        $prefix = $exclusionList->dbPrefix;
        
        if (! $exclusionList->isUriAutoGenerated) {
            $exclusionList->uri = $url ? htmlspecialchars_decode($url) : $this->getUrl($prefix);
        }

        $this->exclusionListRepo->update($prefix, ['import_url' => $exclusionList->uri]);
    }
    
    private function getUrl($prefix)
    {
        $record = $this->exclusionListRepo->find($prefix);     
        return $record ? $record[0]->import_url : '';
    }
    
    private function isReadyForUpdate($prefix)
    {
        $record = $this->exclusionListRepo->find($prefix);
        return $record && $record[0]->ready_for_update === 'Y' ? true : false;
    }
    
    private function downloadExclusionListFiles(ExclusionList $exclusionList)
    {
        return $this->exclusionListDownloader->downloadFiles($exclusionList);        
    }
    
    private function updateRepositoryFiles($latestExclusionListFiles, $prefix)
    {
        if (! $latestExclusionListFiles) {
            //No latest files with which to compare the files in repository - do nothing
            return;
        }

        if (! $this->isRepositoryFileStale($prefix, $latestExclusionListFiles)) {
            //File in database is already up-to-date - do nothing
            info('Repository file(s) for ' . $prefix . ' is already up-to-date.');
            return;
        }

        //Go over each file and insert or update as necessary
        for ($fileIndex = 0; $fileIndex < count($latestExclusionListFiles); $fileIndex++) {
            
            $fileContents = file_get_contents($latestExclusionListFiles[$fileIndex]);
            
            if ($this->hasExistingFileInRepository($prefix, $fileIndex)) {

                $this->updateFileInRepository($fileContents, $prefix, $fileIndex);
                
            } else {

                $this->addFileToRepository($fileContents, $prefix, $fileIndex);
            }
            
        }
        
        $this->setReadyForUpdate($prefix, 'Y');
    }
    
    /**
     * Returns true if any of the files contained by $latestExclusionListFiles
     * is not equal to the currently stored files in the database. Otherwise
     * returns false if there are no files yet in the database or the contents
     * of one of the files in $latestExclusionListFiles is not equal to 
     * @param string $prefix the state prefix
     * @param array $latestExclusionListFiles filenames of the files to compare
     * with their database file counterparts
     * @return boolean
     */
    private function isRepositoryFileStale($prefix, $latestExclusionListFiles)
    {
        
        for ($fileIndex = 0; $fileIndex < count($latestExclusionListFiles); $fileIndex++) {
            
            //Put the file contents from the repository in a temporary file so we don't need to hold it in memory
            $repositoryFileContents = $this->getFileContentsFromRepository($prefix, $fileIndex);
            
            if (! $repositoryFileContents) {
                //No file was stored yet in the file repository - definitely stale
                return true;    
            }
            
            $repositoryTempFile = tempnam(sys_get_temp_dir(), $prefix);
            
            file_put_contents($repositoryTempFile, $repositoryFileContents, LOCK_EX);
            
            //Compare the latest downloaded file and the file we got from the database.
            if (! FileUtils::contentEquals($repositoryTempFile, $latestExclusionListFiles[$fileIndex])) {
                
                unlink($repositoryTempFile);
                return true ;
            }
            
            unlink($repositoryTempFile);
        }
        
        return false;
    }
    
    
    private function setReadyForUpdate($prefix, $value)
    {
        $this->exclusionListRepo->update($prefix, ['ready_for_update' => $value]);
    }
    
    /**
     * Retrieves the blob value from Files table for a given state prefix.
     *
     * @param string $prefix The state prefix
     * @param int $fileIndex the zero-based index of the blob (defaults to 0 if not specified)
     * @return string The img_data of the state
     */
    private function getFileContentsFromRepository($prefix, $fileIndex = 0)
    {
        $record = $this->exclusionListFilesRepo->find($prefix, $fileIndex);
        return $record ? $record[0]->img_data : null;
    }    
    
    private function isExclusionListRecordsEmpty($prefix)
    {
        return $this->exclusionListRepo->isExclusionListRecordsEmpty($prefix);
    }
    
    /**
     * Checks if the file for the given state prefix and file index already
     * exist in the database
     *
     * @param string $prefix The state prefix
     * @param int $fileIndex The file index of the file to check
     * @return boolean
     */
    private function hasExistingFileInRepository($prefix, $fileIndex)
    {
        return $this->exclusionListFilesRepo->exists($prefix, $fileIndex);
    }  
    
    /**
     * Updates the blob data in files table for a given state prefix.
     *
     * @param string $fileContents The contents of the
     * @param string $prefix The state prefix
     *
     * @return void
     */
    private function updateFileInRepository($fileContents, $prefix, $fileIndex)
    {
        $this->exclusionListFilesRepo->update($prefix, $fileIndex, ['img_data' => $fileContents]);
    }
    
    /**
     * Inserts a record to the files table.
     *
     * @param string $fileContents The blob value of the import file
     * @param string $prefix The state prefix
     *
     * @return void
     */
    private function addFileToRepository($fileContents, $prefix, $fileIndex)
    {
        $this->exclusionListFilesRepo->create([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex,
            'img_data' => $fileContents
        ]);
    }

    private function deleteQuiety($exclusionListFiles)
    {
        if (! $exclusionListFiles) {
            return;
        }
         
        try {
            
            $downloadDir = $this->exclusionListDownloader->getDownloadDirectory();
             
            $filesToDelete = [];
            
            foreach ($exclusionListFiles as $file) {
                // Delete the file only if it is in the download directory,
                // otherwise leave it alone since it's a local file specified
                // by the user
                if (strpos($file, $downloadDir) === 0) {
                    $filesToDelete[] = $file;
                }
            }
             
            FileUtils::deleteFiles($filesToDelete);     
            
        } catch (\Exception $e) {
            //quietly handle exceptions here
            info('Encountered an error while trying to cleanup downloaded files : ' . $e->getMessage());
        }

    }
    
    
    private function getErrorMessageFrom(\PDOException $e)
    {
        return sprintf('SQLSTATE[%s]: %s: %s', $e->errorInfo[0], $e->errorInfo[1], $e->errorInfo[2]);
    }
}
