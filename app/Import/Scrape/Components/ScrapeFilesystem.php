<?php

namespace App\Import\Scrape\Components;

use League\Flysystem\Filesystem;

class ScrapeFilesystem extends Filesystem implements ScrapeFilesystemInterface
{
	/**
	 * Get path
	 * @param string $path
	 * @return string
	 */
	public function getPath($path)
	{
		return $this->getAdapter()->applyPathPrefix($path);
	}
}