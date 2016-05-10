<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

class AmbulatorySurgicalCenterMapper extends Basemapper
{
	protected $csvHeaders = [
			'facility_name',
			'address',
			'city',
			'state',
			'zip',
			'license_no',
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
	    return $this->getRosterDbData([
	        'business_name' => $data['facility_name'],
	        'address1' => $data['address'],
	        'city' => $data['city'],
	        'state' => $data['state'],
	        'zip' => $data['zip'],
	        'license_no' => $data['license_no'],
	        'license_effective_date' => $this->getDateFromFormat($data['effective_date']),
	        'license_expiration_date' => $this->getDateFromFormat($data['expiration_date']),
	        'license_status' => $data['status']
	    ]);
	}
}