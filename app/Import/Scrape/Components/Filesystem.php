<?php

namespace App\Import\Scrape\Components;

use League\Flysystem\Filesystem as LeagueFilesystem;

class Filesystem extends LeagueFilesystem implements FilesystemInterface
{
	/**
	 * @var string
	 */
	protected static $folderPath = 'storage/app/scrape';
	
	/**
	 * Get folder path
	 * @return string
	 */
	public static function getFolderPath()
	{
		return static::$folderPath;
	}
}