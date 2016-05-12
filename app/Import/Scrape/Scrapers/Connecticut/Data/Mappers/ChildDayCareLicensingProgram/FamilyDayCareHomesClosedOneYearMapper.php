<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class FamilyDayCareHomesClosedOneYearMapper extends BaseMapper
{
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
	    return $this->getRosterDbData([
	        'first_name' => $data['First Name'],
	        'last_name' => $data['Last Name'],
	        'address1' => $data['Address'],
	        'city' => $data['City'],
	        'zip' => $data['Zip'],
	        'license_no' => $data['License #'],
	        'license_expiration_date' => $this->getDateFromFormat($data['Close Date'])
	    ]);
	}
}