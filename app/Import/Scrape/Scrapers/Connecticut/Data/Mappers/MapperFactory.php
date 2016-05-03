<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

class MapperFactory
{
	protected static $classes = [
			'ambulatory_surgical_centers_recovery_care_centers' => [
					'ambulatory_surgical_center' => AmbulatorySurgicalCenterMapper::class
			],
			'controlled_substances_practitioners_labs_manufacturers' => [
					'manufacturers_of_drugs_cosmetics_and_medical_devices' => ManufacturersDrugsCosmeticsMedicalDevicesMapper::class,
			]
	];
	
	/**
	 * Create by keys
	 * @param string $category
	 * @param string $option
	 * @return MapperInterface
	 */
	public static function createByKeys($category, $option)
	{
		$class = static::$classes[$category][$option];
		
		return new $class;
	}
}