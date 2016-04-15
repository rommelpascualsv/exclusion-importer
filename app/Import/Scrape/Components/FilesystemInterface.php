<?php

namespace App\Import\Scrape\Components;

use League\Flysystem\FilesystemInterface as LeagueFilesystemInterface;

interface FilesystemInterface extends LeagueFilesystemInterface
{
	/**
	 * Get folder path
	 * @return string
	 */
	public static function getFolderPath();
}