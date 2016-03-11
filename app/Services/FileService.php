<?php
namespace App\Services;

use App\File;
use App\Services\Contracts\FileServiceInterface;
use App\Services\Contracts\ImportServiceInterface;

/**
 * Service class that manages the files wherein it updates the database whenever there are 
 * changes from the import file.
 *
 */
class FileService implements FileServiceInterface
{
	protected $importService;
	
	public function __construct(ImportServiceInterface $importService)
	{
		$this->importService = $importService;
	}	
	
	/**
	 * Retrieves the File record in Files table for a given state prefix.
	 *
	 * @param string $prefix The state prefix
	 *
	 * @return file The file record
	 */
	public function getFile($prefix)
	{
		$record = app('db')->table('files')->where('state_prefix', $prefix)->get();
		
		return $record;
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
	
		return $record[0]->auto_import === 'Y' ? true : false;
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
		foreach ($urls as $url)
		{
			$import_url = $url->import_url;
			try 
			{
				if (!$this->isFileSupported($import_url))
				{
					info('File type is not supported.');
					continue;
				}
				
				// get the blob value of import file
				$blob = file_get_contents($import_url);
				
				// checks if state prefix already exists in Files table
				if ($this->isPrefixExists($url->prefix))
				{
					// compares the import file and the one saved in Files table
					if ($blob !== $this->getBlobOfFile($url->prefix))
					{
						// updates the blob column in Files table if imported file is different
						$this->updateBlob($blob, $url->prefix);
						$this->importFile($import_url,  $url->prefix);
					}
				} else
				{
					// inserts a record in Files table if state prefix is not found
					$this->insertFile($blob, $url->prefix, $import_url);
					$this->importFile($import_url,  $url->prefix);
				}
			}
			catch (\ErrorException $e)
			{
				error_log($e->getMessage());
				info($import_url. " Error occured while downloading file. Continuing to next url...");
				continue;
			}
		}
	}
	
	/**
	 * 
	 * 
	 * @param unknown $url
	 * @param unknown $prefix
	 */
	protected function importFile($url, $prefix)
	{
		if ($this->isStateAutoImport($prefix))
		{
			$this->importService->importFile($url, $prefix);
			$this->updateReadyForUpdate($prefix, 'N');
		}
	}
	
	/**
	 * Retrieves an array of import urls saved in the Urls table.
	 * 
	 * @return array
	 */
	protected function getUrls(){
		return app('db')->table('exclusion_lists')->get();
	}
	
	/**
	 * Checks if the state prefix already exists in Files table.
	 * 
	 * @param string $prefix The state prefix
	 * @return boolean
	 */
	protected function isPrefixExists($prefix){
		$files = app('db')->table('files')->where('state_prefix', $prefix)->get();
		
    	return count($files) > 0;
	}
	
	/**
	 * Retrieves the blob value from Files table for a given state prefix.
	 * 
	 * @param string $prefix The state prefix
	 * @return blob
	 */
	protected function getBlobOfFile($prefix){
		$files = app('db')->table('files')->where('state_prefix', $prefix)->get();
    	
    	return count($files) > 0 ? $files[0]->img_data : null;
	}
	
	/**
	 * Inserts a record to the File table.
	 * 
	 * @param blob $blob The blob value of the import file
	 * @param string $prefix The state prefix
	 * @param string $url The import url
	 * @return void
	 */
	private function insertFile($blob, $prefix, $url){
		$file = [];
		$file["file_name"] = $this->getFileName($url);
		$file["state_prefix"] = $prefix;
		$file["img_data"] = $blob;
		$file["ready_for_update"] = 'Y';
		
		info('Saving '.$file['file_name'].'...');
		app('db')->table('files')->insert($file);
	}
	
	/**
	 * Updates the blob data in File table for a given state prefix.
	 * 
	 * @param blob $blob The blob value of the import file
	 * @param string $prefix The state prefix
	 * @return void
	 */
	private function updateBlob($blob, $prefix){
		$affected = app('db')->table('files')->where('state_prefix', $prefix)->update(['img_data' => $blob, 'ready_for_update' => 'Y']);
		
		info($affected.' file/s updated');
	}
	
	/**
	 * Updates the ready_for_update flag in File table.
	 *
	 * @param string $prefix The state prefix
	 * @param string $value The value to set for the flag
	 * @return void
	 */
	private function updateReadyForUpdate($prefix, $value){
		$affected = app('db')->table('files')->where('state_prefix', $prefix)->update(['ready_for_update' => $value]);
	
		info($affected.' file/s updated');
	}
	
	/**
	 * Returns the filename of the url.
	 *
	 * @param string $url The import url
	 * @return string
	 */
	private function getFileName($url)
	{
		return pathinfo($url, PATHINFO_FILENAME).'.'.pathinfo($url, PATHINFO_EXTENSION);
	}
	
	/**
	 * Checks if the import file is currently supported.
	 *
	 * @param string $url The import url
	 * @return boolean
	 */
	private function isFileSupported($url)
	{
		$filetypeArr = ['application/pdf','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/plain','text/csv', 'text/html; charset=utf-8', 'text/html'];
	
		try
		{
			$arrHeaders = get_headers($url, 1);
		}
		catch (\ErrorException $e)
		{
			throw new \ErrorException($e);
		}
	
		return in_array($arrHeaders['Content-Type'], $filetypeArr);
	}
}