<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Maps;

class AmbulatorySurgicalCenterMap extends BaseMap
{
	protected static $csvHeaders = [
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
	
	public function getCsvData()
	{
	}
	
	public function getDbData()
	{
		
	}
}