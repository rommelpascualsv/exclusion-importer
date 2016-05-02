<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

abstract class BaseMapper
{
	/**
	 * Get csv data
	 * @param array $data
	 * @return array
	 */
	public function getCsvData(array $data)
	{
		$dataCount = count($data);
		$csvData = [];
	
		for ($i = 0; $i < $dataCount; $i++) {
			$key = $this->csvHeaders[$i];
			$csvData[$key] = $data[$i];
		}
	
		return $csvData;
	}
	
	/**
	 * Get facility db data
	 * @param string $name
	 * @param string $address1
	 * @param string $address2
	 * @param string $city
	 * @param string $county
	 * @param string $stateCode
	 * @param string $zip
	 * @param string $completeAddress
	 */
	public function getFacilityDbData(
		$name = '',
		$address1 = '',
		$address2 = '',
		$city = '',
		$county = '',
		$stateCode = '',
		$zip = '',
		$completeAddress = ''
	) {
		$dbData = ['name' => $name];		
		
		return $this->getRosterDbData(
				$dbData,
				$address1,
				$address2,
				$city,
				$county,
				$stateCode,
				$zip,
				$completeAddress
		);
	}
	
	/**
	 * Get roster db data
	 * @param array $data
	 * @param string $address1
	 * @param string $address2
	 * @param string $city
	 * @param string $county
	 * @param string $stateCode
	 * @param string $zip
	 * @param string $completeAddress
	 */
	protected function getRosterDbData(
		array $data,
		$address1,
		$address2,
		$city,
		$county,
		$stateCode,
		$zip,
		$completeAddress
	) {
		return array_merge($data, [
				'address1' => $address1,
				'address2' => $address2,
				'city' => $city,
				'county' => $county,
				'state_code' => $stateCode,
				'zip' => $zip,
				'complete_address' => $completeAddress
		]);
	}
}