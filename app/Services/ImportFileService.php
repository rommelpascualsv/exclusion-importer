<?php
namespace App\Services;

use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Services\Contracts\ImportFileServiceInterface;
use Illuminate\Http\Request;
use App\File;
/**
 * Service class that handles the import related processes.
 *
 */
class ImportFileService implements ImportFileServiceInterface
{
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
			->select('exclusion_lists.prefix', 'exclusion_lists.accr', 'exclusion_lists.description', 'exclusion_lists.import_url', 'files.ready_for_update')
			->whereIn('exclusion_lists.prefix', array_keys($lists))
			->get();
		
		$collection = [];
		foreach($states as $state) {
			$collection[$state->prefix] = json_decode(json_encode($state),true);
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
	public function importFile($url, $listPrefix, $shouldSaveFile)
	{
		// 1. Retrieves the corresponding state object
		try {
			$listObject = $this->getStateObject($url, $listPrefix);
		}
		catch(\RuntimeException $e) {
			return $this->createResponse($e->getMessage(), false);
		}
		
		// 2. Checks if state is updateable
		if (empty($url) && !$this->isStateUpdateable($listPrefix)) {
			return $this->createResponse('State is already up-to-date.', false);
		}
		
		// 3. Retrieves data for a given file type
		$listObject->retrieveData();
		
		// 4. Insert records to state table
		$processingService = $this->getListProcessor($listObject);
		$processingService->insertRecords();
		
		// 5. 
		if ($shouldSaveFile) {
			// 'N' will be the value for ready_for_update as the updated records were already inserted to its corresponding state table
			$this->saveFile($listPrefix, $url, 'N');
		} else {
			$this->updateReadyForUpdate($listPrefix, 'N');
		}
				
		// 6. Return successful response
		return $this->createResponse('', true);
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
		$listObject = $listFactory->make($listPrefix);
		
		return $listObject;
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
	 * @return import_url
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
		info('Updated '.$result.' urls for '.$statePrefix);
	
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
		$record = app('db')->table('files')->where('state_prefix', $prefix)->get();
	
		return (count($record) === 0 || $record[0]->ready_for_update === 'Y') ? true : false;
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
		try {
			$listObject = $this->getListObject($listPrefix);
		
			if ($url) {
				$newUri = htmlspecialchars_decode($url);
				$this->updateStateUrl($listPrefix, $newUri);
				$listObject->uri = $newUri;
			} else {
				$listObject->uri = $this->getUrl($listPrefix);
			}
		}
		catch(\RuntimeException $e) {
			throw new \RuntimeException($e->getMessage () . ': ' . $listPrefix );
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
		foreach($states as $state) {
			$collection[$state->prefix] = $state->accr;
		}
		return $collection;
	}
	
	/**
	 * Returns the response object for the given parameters.
	 * 
	 * @param string $message
	 * @param string $isSuccess
	 * 
	 * @return object The response object
	 */
	private function createResponse($message, $isSuccess) 
	{
		return response()->json ([ 
				'success' => $isSuccess,
				'msg' => $message 
		]);
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
		$affected = app('db')->table('files')->where('state_prefix', $prefix)->update(['ready_for_update' => $value]);
	
		info("Updating Ready For Update flag for... ".$prefix." ".$affected.' file/s updated');
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
				
				info("Refreshing... ".$url->prefix);
				
				if (!$this->isFileSupported($import_url)) {
					info('File type is not supported.');
					continue;
				}
				
				$saved = $this->saveFile($url->prefix, $import_url, 'Y');
				
				if ($saved) {
					$this->autoImport($import_url,  $url->prefix);
				}
			}
			catch (\ErrorException $e) {
				error_log($e->getMessage());
				info($import_url. " Error occured while downloading file. Continuing to next url...");
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
	 * If the state is auto import enabled, it will the import the record.
	 *
	 * @param unknown $url
	 * @param unknown $prefix
	 */
	protected function autoImport($url, $prefix)
	{
		if ($this->isStateAutoImport($prefix)) {
			//pass false to indicate that there's no need to save the blob to the files table as it was already saved before this
			$this->importFile($url, $prefix, false);
		}
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
	 * Checks if the state prefix already exists in Files table.
	 *
	 * @param string $prefix The state prefix
	 * 
	 * @return boolean
	 */
	protected function isPrefixExists($prefix)
	{
		$files = app('db')->table('files')->where('state_prefix', $prefix)->get();
	
		return count($files) > 0;
	}
	
	/**
	 * Retrieves the blob value from Files table for a given state prefix.
	 *
	 * @param string $prefix The state prefix
	 * 
	 * @return blob
	 */
	protected function getBlobOfFile($prefix)
	{
		$files = app('db')->table('files')->where('state_prefix', $prefix)->get();
		 
		return count($files) > 0 ? $files[0]->img_data : null;
	}
	
	/**
	 * Handles the insert/update if record in Files table.
	 *
	 * @param string $prefix the state prefix
	 * @param string $import_url the import url
	 * 
	 * @return boolean true if file was inserted/updated otherwise, false
	 */
	private function saveFile($prefix, $import_url, $updateValue)
	{
		// get the blob value of import file
		$blob = file_get_contents($import_url);
	
		// checks if state prefix already exists in Files table
		if ($this->isPrefixExists($prefix)) {
			// compares the import file and the one saved in Files table
			if ($blob !== $this->getBlobOfFile($prefix)) {
				// updates the blob column in Files table if imported file is different
				$this->updateBlob($blob, $prefix);
				$this->updateReadyForUpdate($prefix, $updateValue);
				return true;
			}
		} else {
			// inserts a record in Files table if state prefix is not found
			$this->insertFile($blob, $prefix, $import_url);
			$this->updateReadyForUpdate($prefix, $updateValue);
			return true;
		}
	
		return false;
	}
	
	/**
	 * Inserts a record to the files table.
	 *
	 * @param blob $blob The blob value of the import file
	 * @param string $prefix The state prefix
	 * @param string $url The import url
	 * 
	 * @return void
	 */
	private function insertFile($blob, $prefix, $url){
		$file = [];
		$file["state_prefix"] = $prefix;
		$file["img_data"] = $blob;
		$file["ready_for_update"] = 'Y';
	
		info('Saving blob to FILES table...');
		app('db')->table('files')->insert($file);
	}
	
	/**
	 * Updates the blob data in files table for a given state prefix.
	 *
	 * @param blob $blob The blob value of the import file
	 * @param string $prefix The state prefix
	 * 
	 * @return void
	 */
	private function updateBlob($blob, $prefix){
		$affected = app('db')->table('files')->where('state_prefix', $prefix)->update(['img_data' => $blob]);
	
		info("Updating blob in Files table... ".$affected.' file/s updated');
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
			$arrHeaders = get_headers($url, 1);
		}
		catch (\ErrorException $e) {
			throw new \ErrorException($e);
		}
	
		return in_array($arrHeaders['Content-Type'], $filetypeArr);
	}
}
