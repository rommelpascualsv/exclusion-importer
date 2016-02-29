<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;
/**
 * Interface for import related services 
 *
 */
interface ImportServiceInterface
{

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
	public function importFile($url, $listPrefix);
}