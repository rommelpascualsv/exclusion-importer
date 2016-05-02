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
		return $this->getFacilityDbData(
				$data['facility_name'],
				$data['address'],
				'',
				$data['city'],
				'',
				$data['state'],
				$data['zip']
		);
	}
}