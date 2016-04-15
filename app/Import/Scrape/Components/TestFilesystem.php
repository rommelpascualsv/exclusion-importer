<?php

namespace App\Import\Scrape\Components;

class TestFilesystem extends Filesystem
{
	/**
	 * @var string
	 */
	protected static $folderPath = 'tests/_data/scrape';
}