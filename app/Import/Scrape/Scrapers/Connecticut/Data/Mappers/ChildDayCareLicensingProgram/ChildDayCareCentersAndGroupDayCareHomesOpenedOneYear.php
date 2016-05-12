<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class ChildDayCareCentersAndGroupDayCareHomesOpenedOneYear extends BaseMapper
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
	        'license_effective_date' => $this->getDateFromFormat($data['Initial License Date'])
	    ]);
	}
}