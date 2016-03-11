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
	 * Retrieves the File record in Files table for a given state prefix.
	 *
	 * @param string $prefix The state prefix
	 *
	 * @return file The file record
	 */
	public function getFile($prefix);
	
}