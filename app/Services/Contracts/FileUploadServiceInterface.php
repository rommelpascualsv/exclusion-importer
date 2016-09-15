<?php

namespace App\Services\Contracts;

/**
 * Interface for file upload related services
 *
 */
interface FileUploadServiceInterface
{
	/**
	 * Uploads and stores the given file in the server
	 * 
	 * @return void
	 */
	public function uploadFile(\SplFileInfo $file, $prefix);
}
