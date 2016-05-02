<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

class MapperFactory
{
	protected static $classes = [
			'ambulatory_surgical_centers_recovery_care_centers' => [
					'ambulatory_surgical_center' => AmbulatorySurgicalCenterMapper::class
			]
	];
	
	/**
	 * Create by keys
	 * @param string $category
	 * @param string $option
	 */
	public static function createByKeys($category, $option)
	{
		$class = static::$classes[$category][$option];
		
		return new $class;
	}
}