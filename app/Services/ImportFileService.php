<?php
namespace App\Services;

use App\Events\FileImportEventFactory;
use App\Exceptions\LoggablePDOException;
use App\Import\Lists\ExclusionList;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Repositories\ExclusionListRecordRepository;
use App\Repositories\ExclusionListRepository;
use App\Response\JsonResponse;
use App\Services\Contracts\ImportFileServiceInterface;


/**
 * Service class that handles the import related processes.
 *
 */
class ImportFileService implements ImportFileServiceInterface
{
    use JsonResponse;
    
    private $exclusionListDownloader;
    private $exclusionListRepo;
    private $fileHelper;
    private $exclusionListRecordRepo;
    private $exclusionListStatusHelper;
    
    public function __construct(ExclusionListHttpDownloader $exclusionListHttpDownloader,
                                ExclusionListRepository $exclusionListRepo,
                                ExclusionListRecordRepository $exclusionListRecordRepo,
                                FileHelper $fileHelper,
                                ExclusionListStatusHelper $exclusionListStatusHelper)
    {
        $this->exclusionListDownloader = $exclusionListHttpDownloader;
        $this->exclusionListRepo = $exclusionListRepo;
        $this->exclusionListRecordRepo = $exclusionListRecordRepo;
        $this->fileHelper = $fileHelper;
        $this->exclusionListStatusHelper = $exclusionListStatusHelper;
    }
    
    /**
     * Imports the downloaded file to database
     * @param string $url The url of the exclusion list to import
     * @param string $prefix The state prefix
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
                
                if (! $this->exclusionListStatusHelper->isUpdateRequired($prefix, $hash)) {
                    
                    $message = 'Exclusion list for \'' . $prefix. '\' is already up-to-date';
                    info($message);
                    return $this->createResponse($message, true);
                }
                
                if ($exclusionList->type) {
                    // Set the ExclusionList's uri to the path of the locally downloaded file(s)
                    // so downstream processes would just work with the local copies
                    $exclusionList->uri = implode(',', $exclusionListFiles);
                }
            }
            
            info('Parsing records for ' . $prefix);

            $this->parseRecords($exclusionList);
                
            info('Saving records for ' . $prefix);
            
            $importResults = $this->saveRecords($exclusionList, $hash);
            
            info('File import successfully completed for ' . $prefix . ' in staging server. Import stats : ' . json_encode($importResults));
            
            //$this->exclusionListRecordRepo->pushRecordsToProduction($prefix);
            
            return $this->createResponse('', true, [
                'prefix' => $prefix,
                'importResults' => $importResults
            ]);
            
        } catch (\PDOException $e) {
            
            // Wrap in a LoggablePDOException to avoid having binary data messing up the JSON serialization of the error message
            return $this->onFileImportException($prefix, new LoggablePDOException($e));
            
        } catch (\Exception $e) {
            
            return $this->onFileImportException($prefix, $e);
            
        } finally {
            
            delete_if_in_dir($this->exclusionListDownloader->getDownloadDirectory(), $exclusionListFiles);
        }
    }

    /**
     * Refreshes the records whenever there are changes found in the import file.
     *
     * {@inheritDoc}
     * @see \App\Services\Contracts\ImportFileServiceInterface::refreshRecords()
     */
    public function refreshRecords()
    {
        $exclusionLists = $this->exclusionListRepo->getAllExclusionLists();

        foreach ($exclusionLists as $exclusionList) {
    
            $prefix = $exclusionList->prefix;
            $importUrl = $this->getUrl($prefix);
            $isAutoImport = ($exclusionList->is_auto_import == 1);
            
            $exclusionListFiles = null;
    
            try {
    
                info('Refreshing records for ' . $prefix);
                
                // Creating an instance of an ExclusionList here is needed so lists whose URIs 
                // are updated in the constructor (i.e. via Crawlers) can update their uri field
                $exclusionList = $this->createExclusionList($prefix);
                
                $this->updateUrl($exclusionList, $importUrl, true); 
                
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
    
            } catch (\PDOException $e) {
                
                $this->onRefreshRecordsException($prefix, $importUrl, new LoggablePDOException($e));
            
            } catch (\Exception $e) {

                $this->onRefreshRecordsException($prefix, $importUrl, $e);
    
            } finally {

                delete_if_in_dir($this->exclusionListDownloader->getDownloadDirectory(), $exclusionListFiles);
            }
        }
    }

    /**
     * Retrieves the corresponding list processor based on the passed object.
     *
     * @param ExclusionList $listObject the exclusion list object
     * @return ListProcessor the list processor object
     */
    protected function createListProcessor(ExclusionList $listObject)
    {
        return new ListProcessor($listObject);
    }
    
    /**
     * Returns the corresponding exclusion list object for a given state prefix.
     *
     * @param string $listPrefix the state prefix
     * @return \App\Import\Lists\ExclusionList The state-specific exclusion list object
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
     * @param boolean $skipRepoUpdate true to skip updating the exclusion_lists
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
     * @throws \Exception
     */
    private function downloadExclusionListFiles(ExclusionList $exclusionList)
    {
        $prefix = $exclusionList->dbPrefix;
        
        try {
            
            $files = $this->exclusionListDownloader->downloadFiles($exclusionList);
            
            $this->onFileDownloadSucceeded($prefix);
            
            return $files;
            
        } catch (\PDOException $e) {
            
            $this->onFileDownloadFailed($prefix, new LoggablePDOException($e));
            throw $e;
            
        } catch (\Exception $e) {
            
            $this->onFileDownloadFailed($prefix, $e);
            throw $e;           
        }
        
    }

    /**
     * Updates the files repository with the latest file content and its corresponding
     * hash, if applicable
     * @param $exclusionListFiles
     * @param $prefix
     * @return null|string
     * @throws \Exception
     */
    private function updateFiles($exclusionListFiles, $prefix)
    {
        $versionFile = null;
    
        try {
    
            // If we have multiple files, zip them up into a single archive
            $versionFile = $this->fileHelper->zipMultiple($exclusionListFiles);
    
            // Determine the version type (pdf, xls, html, etc). If multiple files
            // were downloaded, it's automatically 'zip' since we consolidated
            // the files into a single zip archive above. Otherwise, we get the
            // type from the file extension of the first element
            $versionFileType = count($exclusionListFiles) > 1 ? 'zip' : pathinfo($exclusionListFiles[0], PATHINFO_EXTENSION);
    
            $hash = $this->fileHelper->createAndSaveFileHash($versionFile, $versionFileType, $prefix);
    
            // Insert the file contents into the files repository
            $updated = $this->fileHelper->saveFileContents($versionFile, $hash, $prefix);
            
            $this->onFileUpdateSucceeded($prefix, $updated ? ['fileHash' => $hash] : ['message' => 'Last saved file is already up-to-date']);

            return $hash;
    
        } catch (\PDOException $e) {
            
            $this->onFileUpdateFailed($prefix, new LoggablePDOException($e));
            throw $e;
            
        } catch(\Exception $e) {

            $this->onFileUpdateFailed($prefix, $e);
            throw $e;
            
        } finally {
    
            delete_if_in_dir(sys_get_temp_dir(), $versionFile);
        }
    }

    private function parseRecords(ExclusionList $exclusionList)
    {
        $prefix = $exclusionList->dbPrefix;
        
        try {
            
            $exclusionList->retrieveData();
            
            $this->onFileParseSucceeded($prefix);
            
        } catch (\PDOException $e) {
            
            $this->onFileParseFailed($prefix, new LoggablePDOException($e));
            throw $e;
            
        } catch (\Exception $e) {
            
            $this->onFileParseFailed($prefix, $e);
            throw $e;
        }
        
    }
    
    private function saveRecords(ExclusionList $exclusionList, $lastImportedHash)
    {
        $prefix = $exclusionList->dbPrefix;
        
        try {
            
            $this->createListProcessor($exclusionList)->insertRecords();
            
            return $this->onRecordsSaveSucceeded($prefix, $lastImportedHash);
            
        } catch (\PDOException $e) {
            
            $this->onRecordsSaveFailed($prefix, new LoggablePDOException($e));
            throw $e;
            
        } catch (\Exception $e) {
            
            $this->onRecordsSaveFailed($prefix, $e);
            throw $e;
        }
        
    }
    
    private function now()
    {
        return date('Y-m-d H:i:s');        
    }
    
    private function onFileDownloadSucceeded($prefix)
    {
        event('file.download.succeeded', FileImportEventFactory::newFileDownloadSucceeded()->setObjectId($prefix));
    }
    
    private function onFileDownloadFailed($prefix, \Exception $e)
    {
        event('file.download.failed', FileImportEventFactory::newFileDownloadFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to download file : ' . $e->getMessage()]))
        );
    }
    
    private function onFileUpdateSucceeded($prefix, $results = null)
    {
        $eventPayload = FileImportEventFactory::newFileUpdateSucceeded()->setObjectId($prefix);
        
        if ($results) {
            $eventPayload->setDescription(json_encode($results));
        }
        
        event('file.update.succeeded', $eventPayload);
    }
    
    private function onFileUpdateFailed($prefix, \Exception $e)
    {
        event('file.update.failed', FileImportEventFactory::newFileUpdateFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to update file : ' . $e->getMessage()]))
        );
    }
    
    private function onFileParseSucceeded($prefix)
    {
        event('file.parse.succeeded', FileImportEventFactory::newFileParseSucceeded()->setObjectId($prefix));
    }
    
    private function onFileParseFailed($prefix, \Exception $e)
    {
        event('file.parse.failed', FileImportEventFactory::newFileParseFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to parse file content : ' . $e->getMessage()]))
       );
    }
    
    private function onRecordsSaveSucceeded($prefix, $lastImportedHash)
    {
        $now = $this->now();
        
        $importStats = $this->exclusionListRecordRepo->getImportStats($prefix);

        $importResults = [
            'fileHash' => $lastImportedHash,
            'importStats' => $importStats,
            'importTS' => $now
        ];

        $this->updateExclusionListWith($importResults, $prefix);
        
        event('file.saverecords.succeeded', FileImportEventFactory::newSaveRecordsSucceeded()
            ->setObjectId($prefix)
            ->setTimestamp($now)
            ->setDescription(json_encode($importResults))
        );
        
        return $importResults;
        
    }
    
    private function updateExclusionListWith($importResults, $prefix)
    {
        $this->exclusionListRepo->update($prefix, [
            'last_imported_hash'  => $importResults['fileHash'],
            'last_imported_date'  => $importResults['importTS'],
            'last_import_stats' => json_encode($importResults['importStats'])
        ]);
    }    
    
    private function onRecordsSaveFailed($prefix, \Exception $e)
    {
        event('file.saverecords.failed', FileImportEventFactory::newSaveRecordsFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to save records : ' . $e->getMessage()]))
        );
    }
    
    private function onFileImportException($prefix, \Exception $e)
    {
        error('An error occurred while trying to import exclusion list for ' . $prefix . ' : ' . $e->getMessage());
        return $this->createResponse('Error importing exclusion list for \'' . $prefix . '\' : ' . $e->getMessage(), false);
    }
    
    private function onRefreshRecordsException($prefix, $importUrl, \Exception $e)
    {
        error('An error occurred while trying to refresh ' . $prefix . ' with url ' . $importUrl . ' : ' . $e->getMessage());
    }    
    
}
