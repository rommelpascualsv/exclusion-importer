<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class TemporaryPermitToPracticePharmacyMapper extends BaseMapper
{
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
	    return $this->getRosterDbData([
	        'first_name' => $data['FIRST NAME'],
            'last_name' => $data['LAST NAME'],
            'city' => $data['CITY'],
            'county' => $data['COUNTY'],
            'state' => $data['STATE'],
            'zip' => $data['ZIP'],
            'license_no' => $data['LICENSE'],
            'license_effective_date' => $this->getDateFromFormat($data['EFFECTIVE DATE']),
            'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
            'license_status' => $data['STATUS'],
            'license_status_reason' => $data['REASON']
	    ]);
	}
}