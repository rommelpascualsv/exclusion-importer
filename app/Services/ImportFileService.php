<?php
namespace App\Services;

use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Services\Contracts\ImportFileServiceInterface;
use Illuminate\Http\Request;
use App\Response\JsonResponse;
use App\Utils\FileUtils;

/**
 * Service class that handles the import related processes.
 *
 */
class ImportFileService implements ImportFileServiceInterface
{
    use JsonResponse;
    
    private $exclusionListDownloader;
    
    public function __construct() {
        $this->exclusionListDownloader = new ExclusionListHttpDownloader();
    }
    
    /**
     * Retrieves a list of active states to show in the import page.
     *
     * @return array - from the getActiveStateList method and records retrieved from the exclusion_lists and files tables
     */
    public function getExclusionList()
    {
        $lists = $this->getActiveStateList();
        
        $states = app('db')->table('exclusion_lists')
            ->leftJoin('files', 'exclusion_lists.prefix', '=', 'files.state_prefix')
            ->select('exclusion_lists.prefix', 'exclusion_lists.accr', 'exclusion_lists.description', 'exclusion_lists.import_url', 'exclusion_lists.ready_for_update')
            ->whereIn('exclusion_lists.prefix', array_keys($lists))
            ->get();
        
        $collection = [];
        foreach ($states as $state) {
            $collection[$state->prefix] = json_decode(json_encode($state), true);
        }
        
        return array_merge_recursive($lists, $collection);
    }
    
    /**
     * Imports the downloaded file to database
     * @param $request The Request object from frontend
     * @param $listPrefix The state prefix
     * @param $shouldSaveFile boolean to determine if the record needs to be saved in files table
     *
     * @return object The object containing the result of the operation
     */
    public function importFile($url, $listPrefix)
    {
        
        try {
            // 1. Retrieves the corresponding state object
            info("Trying to import file for " . $listPrefix);
            
            $listObject = $this->getStateObject($url, $listPrefix);
            
            // 2. Download the raw exclusion list file(s) to a local folder
            $latestExclusionListFiles = $this->exclusionListDownloader->downloadFiles($listObject);
            
            // 3. Update the blobs of the files in the database  with the latest downloaded files (if necessary)
            $this->updateRepositoryFiles($latestExclusionListFiles, $listPrefix);
            
            // 4. Checks if state is updateable
            if (! $this->isStateUpdateable($listPrefix)) {
                info($listPrefix . ": State is already up-to-date");
                FileUtils::deleteFiles($latestExclusionListFiles);
                return $this->createResponse('State is already up-to-date.', true);
            }
            
            if ($latestExclusionListFiles) {
                //Set the ExclusionList's uri to the path of the locally downloaded file(s)
                //so downstream processes would just work with a local copy instead
                $listObject->uri = implode(",", $latestExclusionListFiles);
            }
            
            // 4. Retrieves data for a given file type
            $listObject->retrieveData();
            
            // 5. Insert records to state table
            $processingService = $this->getListProcessor($listObject);
            $processingService->insertRecords();
            
            //6. Since we already updated state records, set ready_for_update to N
            $this->updateReadyForUpdate($listPrefix, 'N');
            
            FileUtils::deleteFiles($latestExclusionListFiles);
            
            // 7. Return successful response
            return $this->createResponse('', true);
            
        } catch (\PDOException $e) {
            
            info("Encountered an error while trying to import " . $listPrefix . ": " . $e->getMessage());
            
            return $this->createResponse($this->getErrorMessageFrom($e), false);
            
        } catch (\Exception $e) {
            
            info("Encountered an error while trying to import " . $listPrefix . ": " . $e->getMessage());
            
            return $this->createResponse($e->getMessage(), false);
            
        }
    }
    
    private function updateRepositoryFiles($latestExclusionListFiles, $listPrefix)
    {
        if (!$latestExclusionListFiles) {
            //No latest files with which to compare the files in repository - do nothing
            return;
        }
            
        if (! $this->isRepositoryFileStale($listPrefix, $latestExclusionListFiles) && ! $this->isStateRecordsEmpty($listPrefix)) {
            //File in database is already up-to-date - do nothing
            return;
        }
        
        //Go over each file and insert or update as necessary
        for ($fileIndex = 0; $fileIndex < count($latestExclusionListFiles); $fileIndex++) {
            
            $fileContents = file_get_contents($latestExclusionListFiles[$fileIndex]);
            
            if ($this->hasExistingFileInRepository($listPrefix, $fileIndex)) {
                
                $this->updateFileInRepository($fileContents, $listPrefix, $fileIndex);
                
            } else {
                
                $this->addFileToRepository($fileContents, $listPrefix, $fileIndex);
            }
            
        }
        
        $this->updateReadyForUpdate($listPrefix, 'Y');
         
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
    
    private function isStateRecordsEmpty($prefix)
    {
        return app('db')->table($prefix . '_records')->count() === 0;
    }
    
    /**
     * Returns the corresponding exclusion list object for a given state prefix.
     *
     * @param string $listPrefix the state prefix
     *
     * @return object The state-specific exclusion list object
     */
    protected function getListObject($listPrefix)
    {
        $listFactory = new ListFactory();
        return $listFactory->make($listPrefix);
    }
    
    /**
     * Retrieves the corresponding list processor based on the passed object.
     *
     * @param object $listObject the exclusion list object
     *
     * @return object the list processor object
     */
    protected function getListProcessor($listObject)
    {
        return new ListProcessor($listObject);
    }
    
    /**
     * Retrieves the import_url from the exclusion_lists table for a given state prefix.
     *
     * @param string $prefix The state prefix
     *
     * @return string import_url
     */
    protected function getUrl($prefix)
    {
        $record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
    
        return $record[0]->import_url;
    }
    
    /**
     * Updates the url of the state whenever a url is specified in the exclusion importer page.
     *
     * @param string $statePrefix
     * @param string $stateUrl
     */
    protected function updateStateUrl($statePrefix, $stateUrl)
    {
        $result = app('db')->table('exclusion_lists')->where('prefix', $statePrefix)->update(['import_url' => $stateUrl]);
        info("Updated " . $result . " urls for " . $statePrefix);
    
        return $result;
    }
    
    /**
     * Checks if state prefix is updateable or not.
     *
     * @param sring $prefix The state prefix
     *
     * @return boolean true if state is updateable otherwise false
     */
    protected function isStateUpdateable($prefix)
    {
        $record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
    
        return ($record[0]->ready_for_update === 'Y') ? true : false;
    }
    
    /**
     * Retrieves the corresponding state object for a given state prefix.
     *
     * @param string $url
     * @param string $listPrefix
     *
     * @return object The state object
     * @throws \RuntimeException
     */
    private function getStateObject($url, $listPrefix)
    {
        $listObject = $this->getListObject($listPrefix);
    
        if ($url) {
            $newUri = htmlspecialchars_decode($url);
            $this->updateStateUrl($listPrefix, $newUri);
            $listObject->uri = $newUri;
        } else {
            $listObject->uri = $this->getUrl($listPrefix);
        }
    
        return $listObject;
    }
    
    /**
     * Returns the supported state list.
     *
     * @return list The prefix-state list
     */
    private function getActiveStateList()
    {
        $states = app('db')->table('exclusion_lists')->where('is_active', 1)->get();
        
        $collection = [];
        foreach ($states as $state) {
            $collection[$state->prefix] = $state->accr;
        }
        return $collection;
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
    
        info("Updating Ready For Update flag for... " . $prefix . " " . $affected . " row(s) updated");
    }
    
    /**
     * Refreshes the records whenever there are changes found in the import file.
     *
     * {@inheritDoc}
     * @see \App\Services\Contracts\FileService::refreshRecords()
     */
    public function refreshRecords()
    {
        $urls = $this->getUrls();
    
        // iterate import urls
        foreach ($urls as $url) {
            $import_url = $url->import_url;
            try {
                info("Trying to refresh records for... " . $url->prefix);
                
                if (empty($import_url)) {
                    info("Import url must not be null or empty.");
                    continue;
                }
                
                if (!$this->isFileSupported($import_url)) {
                    info("File type is not supported for " . $url->prefix . ": " . $import_url);
                    continue;
                }
                
                if ($this->isStateAutoImport($prefix)) {
                    
                    //pass false to indicate that there's no need to save the blob to the files table as it was already saved before this
                    info("Auto importing... " . $prefix);
                    $this->importFile($url, $prefix, false);
                    
                } else {
                    
                    //If the list is not auto-importable, just update its file repository copy to the latest
                    $listObject = $this->getStateObject($url, $listPrefix);
                    
                    $latestExclusionListFiles = $this->exclusionListDownloader->downloadFiles($listObject);
                    
                    $this->updateRepositoryFiles($latestExclusionListFiles, $listPrefix);
                    
                    FileUtils::deleteFiles($latestExclusionListFiles);
                }

            } catch (\ErrorException $e) {
                
                error_log($e->getMessage());
                info($import_url . " Error occured while downloading file. Continuing to next url...");
                continue;
            }
        }
    }
    
    /**
     * Checks if state can be auto imported.
     *
     * @param sring $prefix The state prefix
     *
     * @return boolean true if state can be auto imported, otherwise false
     */
    protected function isStateAutoImport($prefix)
    {
        $record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
        return $record[0]->is_auto_import == 1 ? true : false;
    }
    
    /**
     * Retrieves an array of import urls saved in the exclusion_lists table.
     *
     * @return array
     */
    protected function getUrls()
    {
        return app('db')->table('exclusion_lists')->get();
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
        info('Saving file content of ' . $prefix . '-' . $fileIndex .' to files table');
        app('db')->table('files')
                 ->insert([
                    'state_prefix' => $prefix,
                    'img_data_index' => $fileIndex,
                    'img_data' => $fileContents 
                 ]);
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
    
        info("Updated blob of " . $prefix . "in files table... " . $affected . " file(s) updated");
    }
    
    /**
     * Checks if the import file is currently supported.
     *
     * @param string $url The import url
     *
     * @return boolean
     */
    private function isFileSupported($url)
    {
        $filetypeArr = ['application/pdf','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/plain','text/csv', 'text/html; charset=utf-8', 'text/html'];
    
        try {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                info("This is not a valid url: " . $url);
                return false;
            }
            $arrHeaders = get_headers($url, 1);
            $arrHeadersCopy = array_change_key_case($arrHeaders, CASE_LOWER);
        } catch (\ErrorException $e) {
            throw new \ErrorException($e);
        }
    
        return in_array($arrHeadersCopy['content-type'], $filetypeArr);
    }
    
    private function getErrorMessageFrom(\PDOException $e)
    {
        return sprintf('SQLSTATE[%s]: %s: %s', $e->errorInfo[0], $e->errorInfo[1], $e->errorInfo[2]);
    }
}
