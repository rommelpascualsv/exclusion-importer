<?php
namespace App\Services;

use App\Import\Lists\ExclusionList;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Response\JsonResponse;
use App\Services\Contracts\ImportFileServiceInterface;
use App\Utils\FileUtils;
use Symfony\Component\Debug\Exception\FatalErrorException;

/**
 * Service class that handles the import related processes.
 *
 */
class ImportFileService implements ImportFileServiceInterface
{
    use JsonResponse;
    
    private $exclusionListDownloader;
    
    public function __construct(ExclusionListHttpDownloader $exclusionListHttpDownloader = null) {
        $this->exclusionListDownloader = $exclusionListHttpDownloader ? $exclusionListHttpDownloader : new ExclusionListHttpDownloader();
    }
    
    /**
     * Retrieves a list of active states to show in the import page.
     *
     * @return array - from the getActiveStateList method and records retrieved from the exclusion_lists and files tables
     */
    public function getExclusionList()
    {
        return $this->getActiveExclusionLists();
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
            
            // 1. Create ExclusionList class
            $exclusionList = $this->createExclusionList($prefix);
            
            // 2. Update the uri of the exclusion list
            $this->updateUri($exclusionList, $url);
            
            if (! trim($exclusionList->uri)) {
                return $this->createResponse('No URL was specified for : ' . $prefix, false);
            }
            
            // 3. Download the raw exclusion list file(s) to a local folder
            $latestExclusionListFiles = $this->downloadExclusionListFiles($exclusionList);
            
            // 4. Update the files stored in the database with the latest downloaded files if the downloaded files
            // are not the same with the database copies
            $this->updateRepositoryFiles($latestExclusionListFiles, $prefix);
            
            // 5. Checks if state is updateable
            if (! $this->isForUpdate($prefix) && ! $this->isStateRecordsEmpty($prefix)) {
                info($prefix . ": State is already up-to-date.");
                return $this->createResponse('State is already up-to-date.', true);
            }
            
            info('Starting exclusion list import for ' . $prefix);
            
            if ($latestExclusionListFiles) {
                // Set the ExclusionList's uri to the path of the locally downloaded file(s)
                // so downstream processes would just work with the local copies
                $exclusionList->uri = implode(",", $latestExclusionListFiles);
            }
            
            // 6. Retrieves data for a given file type
            $exclusionList->retrieveData();
            
            // 7. Insert records to state table
            $this->createListProcessor($exclusionList)->insertRecords();
            
            // 8. Since we already updated state records, set ready_for_update to N
            $this->updateReadyForUpdate($prefix, 'N');
            
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
            
            $this->cleanupDownloadedExclusionListFiles($latestExclusionListFiles);
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
        $exclusionLists = $this->getExclusionLists();
    
        // iterate import urls
        foreach ($exclusionLists as $exclusionList) {
    
            $prefix = $exclusionList->prefix;
            $import_url = $exclusionList->import_url;
            $latestExclusionListFiles = null;
    
            try {
    
                info('Refreshing records for ' . $prefix);
    
                if (empty($import_url)) {
                    info("Import url must not be null or empty.");
                    continue;
                }
    
                if ($this->isStateAutoImport($prefix)) {
    
                    //pass false to indicate that there's no need to save the blob to the files table as it was already saved before this
                    info('Performing auto-import for ' . $prefix);
    
                    $this->importFile($import_url, $prefix);
    
                    info('Auto-import complete for ' . $prefix);
    
                } else {
    
                    info('\''. $prefix . '\' is not configured for auto import. Updating file repository...');
                    //If the list is not auto-importable, just update its file repository copy to the latest
                    $latestExclusionListFiles = $this->exclusionListDownloader->downloadFiles($this->createExclusionList($prefix));
    
                    $this->updateRepositoryFiles($latestExclusionListFiles, $prefix);
                }
    
            } catch (\Exception $e) {
                
                $errorMessage = 'An error occurred while trying to refresh ' . $prefix . ' with url ' . $import_url . ' : ' . $e->getMessage();
                error_log($errorMessage);
                info($errorMessage);
    
            } finally {

                $this->cleanupDownloadedExclusionListFiles($latestExclusionListFiles);
            }
        }
    }    
    
    /**
     * Returns the supported state list.
     *
     * @return list The prefix-state list
     */
    private function getActiveExclusionLists()
    {
        $states = app('db')->table('exclusion_lists')->where('is_active', 1)->get();
    
        $collection = [];
        foreach ($states as $state) {
            $collection[$state->prefix] = json_decode(json_encode($state), true);
        }
        return $collection;
    }
    
    /**
     * Returns the corresponding exclusion list object for a given state prefix.
     *
     * @param string $listPrefix the state prefix
     *
     * @return object The state-specific exclusion list object
     */
    private function createExclusionList($listPrefix)
    {
        $listFactory = new ListFactory();
        return $listFactory->make($listPrefix);
    }
    
    /**
     * Updates the uri of the exclusion list with the given uri
     *
     * @param ExclusionList $exclusionList
     * @param string $url
     */
    private function updateUri(ExclusionList $exclusionList, $url)
    {
    
        if (! $exclusionList->isUriAutoGenerated) {
    
            if ($url) {
                $newUri = htmlspecialchars_decode($url);
                $exclusionList->uri = $newUri;
            } else {
                $exclusionList->uri = $this->getExclusionListUrl($listPrefix);
            }
    
        }
    
        $prefix = $exclusionList->dbPrefix;
    
        $result = app('db')->table('exclusion_lists')->where('prefix', $prefix)->update(['import_url' => $url]);
    
        info("Updated " . $result . " urls for " . $prefix);
    }
    
    /**
     * Retrieves the import_url from the exclusion_lists table for a given state prefix.
     * @param string $prefix The state prefix
     * @return string import_url
     */
    private function getExclusionListUrl($prefix)
    {
        $record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
    
        return $record[0]->import_url;
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
        
        $this->updateReadyForUpdate($prefix, 'Y');
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
    
    /**
     * Retrieves the blob value from Files table for a given state prefix.
     *
     * @param string $prefix The state prefix
     * @param int $fileIndex the zero-based index of the blob (defaults to 0 if not specified)
     * @return string The img_data of the state
     */
    private function getFileContentsFromRepository($prefix, $fileIndex)
    {
        $files = app('db')
        ->table('files')
        ->where([
            'state_prefix'   => $prefix,
            'img_data_index' => $fileIndex
        ])->get();
         
        return count($files) > 0 ? $files[0]->img_data : null;
    }    
    
    private function isStateRecordsEmpty($prefix)
    {
        return app('db')->table($prefix . '_records')->count() === 0;
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
        return app('db')->table('files')
        ->where([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex
        ])
        ->count() > 0;
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
        $affected = app('db')->table('files')
        ->where([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex
        ])
        ->update(['img_data' => $fileContents]);
    
        info('Updated file content of ' . $prefix . '-' . $fileIndex . ' in files table . ' . $affected . ' file(s) updated');
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
        app('db')->table('files')
        ->insert([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex,
            'img_data' => $fileContents
        ]);
    
        info('Saved file content of ' . $prefix . '-' . $fileIndex .' to files table');
    }
    
    /**
     * Updates the ready_for_update flag in files table.
     *
     * @param string $prefix The state prefix
     * @param string $value The value to set for the flag
     * @return void
     */
    private function updateReadyForUpdate($prefix, $value)
    {
        $affected = app('db')->table('exclusion_lists')->where('prefix', $prefix)->update(['ready_for_update' => $value]);
    
        info('Updated \'Ready For Update\' flag for ' . $prefix . ' to \'' . $value . '\'.');
    }    
    
    /**
     * Checks if state prefix is updateable or not.
     *
     * @param sring $prefix The state prefix
     *
     * @return boolean true if state is updateable otherwise false
     */
    private function isForUpdate($prefix)
    {
        $record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
    
        return ($record[0]->ready_for_update === 'Y') ? true : false;
    }
    
    /**
     * Retrieves the corresponding list processor based on the passed object.
     * @param object $listObject the exclusion list object
     * @return object the list processor object
     */
    private function createListProcessor(ExclusionList $listObject)
    {
        return new ListProcessor($listObject);
    }
    
    /**
     * Retrieves all exclusion lists in the exclusion lists table
     *
     * @return array
     */
    private function getExclusionLists()
    {
        return app('db')->table('exclusion_lists')->get();
    }
    
    /**
     * Checks if state can be auto imported.
     *
     * @param sring $prefix The state prefix
     *
     * @return boolean true if state can be auto imported, otherwise false
     */
    private function isStateAutoImport($prefix)
    {
        $record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
        return $record[0]->is_auto_import == 1 ? true : false;
    }    
    
    private function cleanupDownloadedExclusionListFiles($exclusionListFiles)
    {
        if (! $exclusionListFiles) {
            return;
        }
         
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
    }    
    
    private function getErrorMessageFrom(\PDOException $e)
    {
        return sprintf('SQLSTATE[%s]: %s: %s', $e->errorInfo[0], $e->errorInfo[1], $e->errorInfo[2]);
    }
}
