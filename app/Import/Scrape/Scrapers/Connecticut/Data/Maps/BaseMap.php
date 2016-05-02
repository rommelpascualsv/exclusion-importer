<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Maps;

class BaseMap implements MapInterface
{	
	/**
	 * Get csv map
	 * @return array
	 */
	public static function getCsvHeaders()
	{
		return static::$csvHeaders;
	}
}