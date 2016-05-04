<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

abstract class BaseMapper implements MapperInterface
{
	/**
	 * @var array
	 */
	protected $csvHeaders = [];
	
	/**
	 * @var int
	 */
	protected $csvHeadersCount;
	
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	abstract public function getDbData(array $data);
	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->csvHeadersCount = count($this->csvHeaders);
	}
	
	/**
	 * Get csv data
	 * @param array $data
	 * @return array
	 */
	public function getCsvData(array $data)
	{
		$csvData = [];
	
		for ($i = 0; $i < $this->csvHeadersCount; $i++) {
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
		return $this->getRosterDbData(
				CsvImporter::TYPE_FACILITY,
				['name' => $name],
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
	 * Get person db data
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $address1
	 * @param string $address2
	 * @param string $city
	 * @param string $county
	 * @param string $stateCode
	 * @param string $zip
	 * @param string $completeAddress
	 */
	public function getPersonDbData(
		$firstName = '',
		$lastName = '',
		$address1 = '',
		$address2 = '',
		$city = '',
		$county = '',
		$stateCode = '',
		$zip = '',
		$completeAddress = ''
	) {		
		return $this->getRosterDbData(
				CsvImporter::TYPE_PERSON,
				[
						'first_name' => $firstName,
						'last_name' => $lastName
				],
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
	 * @param string $type
	 * @param array $customValues
	 * @param string $address1
	 * @param string $address2
	 * @param string $city
	 * @param string $county
	 * @param string $stateCode
	 * @param string $zip
	 * @param string $completeAddress
	 */
	protected function getRosterDbData(
			$type,
			array $customValues,
			$address1,
			$address2,
			$city,
			$county,
			$stateCode,
			$zip,
			$completeAddress
	) {
		return [
				'type' => $type,
				'values' => array_merge($customValues, [
						'address1' => $address1,
						'address2' => $address2,
						'city' => $city,
						'county' => $county,
						'state_code' => $stateCode,
						'zip' => $zip,
						'complete_address' => $completeAddress
				])
		];
	}
}