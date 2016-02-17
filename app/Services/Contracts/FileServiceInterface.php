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
	 */
	public function refreshRecords();
}