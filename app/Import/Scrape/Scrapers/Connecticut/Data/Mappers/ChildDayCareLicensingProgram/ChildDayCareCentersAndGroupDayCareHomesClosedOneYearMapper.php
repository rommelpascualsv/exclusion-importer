<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class ChildDayCareCentersAndGroupDayCareHomesClosedOneYearMapper extends BaseMapper
{
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
	    return $this->getRosterDbData([
	        'business_name' => $data['Name'],
	        'address1' => $data['Address'],
	        'city' => $data['City'],
	        'zip' => $data['Zip'],
	        'license_no' => $data['License #'],
	        'license_expiration_date' => $this->getDateFromFormat($data['Close Date'])
	    ]);
	}
}