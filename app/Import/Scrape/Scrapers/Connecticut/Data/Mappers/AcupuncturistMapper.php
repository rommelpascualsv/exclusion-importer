<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

class AcupuncturistMapper extends Basemapper
{
	protected $csvHeaders = [
			'license_no',
			'first_name',
			'last_name',
			'address1',
			'address2',  
			'city',
			'state',   
			'zip',  
			'county',
			'status',
			'reason',
			'issue_date',
			'expiration_date'
	];
	
	
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data)
	{
		return $this->getPersonDbData(
				$data['first_name'],
				$data['last_name'],
				$data['address1'],
				$data['address2'],
				$data['city'],
				$data['county'],
				$data['state'],
				$data['zip']
		);
	}
}