<?php

namespace App\Services\Contracts;

/**
 * Interface for import file related services 
 *
 */
interface ImportFileServiceInterface
{

	/**
	 * Refreshes the records of Files table.
	 * 
	 * @return void
	 */
	public function refreshRecords();
	
	/**
	 * Retrieves the exclusion list
	 *
	 * @return list The exclusion list
	 */
	public function getExclusionList();
	
	/**
	 * Imports the downloaded file to database
	 * @param $request The Request object from frontend
	 * @param $listPrefix The state prefix
	 *
	 * @return object The object containing the result of the operation
	 */
	public function importFile($url, $listPrefix, $shouldSaveFile);
	
}
