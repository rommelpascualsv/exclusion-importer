<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

class ManufacturersDrugsCosmeticsMedicalDevicesMapper extends Basemapper 
{
	protected $csvHeaders = [
			'manufacturer_name',
			'address',
			'city',
			'state',
			'zip',
			'registration',
			'status',
			'effective_date',
			'expiration_date'
	];
	
	
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
		return $this->getRosterDbData(
		    '',
		    '', 
		    $data['manufacturer_name'], 
		    $data['address'], 
		    '', 
		    $data['city'], 
		    '', 
		    $data['state'], 
		    $data['zip'], 
		    $data['registration'], 
		    $this->getDbDateFromFormat($data['effective_date']), 
		    $this->getDbDateFromFormat($data['expiration_date']), 
		    $data['status'],
		    ''
	    );
	}
}