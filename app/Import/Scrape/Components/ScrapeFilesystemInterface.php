<?php

namespace App\Import\Scrape\Components;

use League\Flysystem\FilesystemInterface;

/**
 * {@inheritDoc}
 */
interface ScrapeFilesystemInterface extends FilesystemInterface
{
	const PATH = 'storage/app/scrape';
	const TEST_PATH = 'tests/_data/scrape';
	
	/**
	 * Get path
	 * @param string $path
	 * @return string
	 */
	public function getPath($path);
}