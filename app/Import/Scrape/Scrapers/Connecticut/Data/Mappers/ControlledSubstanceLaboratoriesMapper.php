<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

class ControlledSubstanceLaboratoriesMapper extends Basemapper
{
	protected $csvHeaders = [
	    'contact_first_name',
	    'contact_last_name',
	    'business_name',
	    'address',
	    'city',
	    'state',
	    'zip',
	    'license_number',
	    'status',
	    'effective_date',
	    'expiration_date',
	];
	
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
	    return $this->getRosterDbData(
	        $data['contact_first_name'],
	        $data['contact_last_name'],
	        $data['business_name'],
	        $data['address'],
	        '',
	        $data['city'],
	        '', 
	        $data['state'], 
	        $data['zip'],
	        $data['license_number'],
	        $this->getDbDateFromFormat($data['effective_date']),
	        $this->getDbDateFromFormat($data['expiration_date']),
	        $data['status'],
	        ''
        );
	}
}