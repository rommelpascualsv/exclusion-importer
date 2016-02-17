<?php

namespace App\Services\Contracts;

/**
 * Interface for file related services 
 *
 */
interface FileServiceInterface
{

	/**
	 * Refreshes the records of Files table.
	 * 
	 * @return void
	 */
	public function refreshRecords();
	
	/**
	 * Checks if state prefix is updateable or not.
	 *
	 * @param sring $prefix The state prefix
	 * 
	 * @return boolean true if state is updateable otherwise false
	 */
	public function isStateUpdateable($prefix);
	
	/**
	 * Retrieves the Url record from the URLS table for a given state prefix.
	 * 
	 * @param string $prefix The state prefix
	 * 
	 * @return url The Url record
	 */
	public function getUrl($prefix);
	
	/**
	 * Retrieves the File record in Files table for a given state prefix.
	 *
	 * @param string $prefix The state prefix
	 *
	 * @return file The file record
	 */
	public function getFile($prefix);
	
	/**
	 * Updates the url of the state whenever a url is specified in the exclusion importer page.
	 *
	 * @param string $statePrefix
	 * @param string $stateUrl
	 */
	public function updateStateUrl($statePrefix, $stateUrl);
}