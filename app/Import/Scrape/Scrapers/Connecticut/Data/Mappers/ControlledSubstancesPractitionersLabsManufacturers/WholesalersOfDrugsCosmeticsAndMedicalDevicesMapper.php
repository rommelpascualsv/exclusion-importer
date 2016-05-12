<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class WholesalersOfDrugsCosmeticsAndMedicalDevicesMapper extends BaseMapper
{
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{   
	    return $this->getRosterDbData([
            'business_name' => $data['WHOLESALER NAME'],
            'address1' => $data['ADDRESS'],
            'city' => $data['CITY'],
            'state' => $data['STATE'],
            'zip' => $data['ZIP'],
            'license_no' => $data['REGISTRATION'],
            'license_effective_date' => $this->getDateFromFormat($data['EFFECTIVE DATE']),
            'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
            'license_status' => $data['STATUS']
	    ]);
	}
}