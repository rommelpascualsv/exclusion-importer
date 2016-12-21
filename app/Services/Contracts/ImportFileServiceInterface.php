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
     * Imports the downloaded file to database
     * @param $url the URL of the file to import
     * @param $listPrefix the prefix of the list
     * @return mixed
     */
	public function importFile($url, $listPrefix);
}
