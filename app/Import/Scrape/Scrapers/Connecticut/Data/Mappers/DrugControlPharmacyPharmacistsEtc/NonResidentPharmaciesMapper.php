<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class NonResidentPharmaciesMapper extends BaseMapper
{
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
	    return $this->getRosterDbData([
            'business_name' => $data['NONRESIDENT PHARMACY NAME'],
            'address1' => $data['ADDRESS'],
            'city' => $data['CITY'],
            'state' => $data['STATE'],
            'zip' => $data['ZIP'],
            'license_no' => $data['LICENSE'],
            'license_effective_date' => $this->getDateFromFormat($data['EFFECTIVE DATE']),
            'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
            'license_status' => $data['STATUS']
	    ]);
	}
}