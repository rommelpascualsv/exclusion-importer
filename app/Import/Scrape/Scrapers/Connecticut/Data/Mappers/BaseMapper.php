<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use Carbon\Carbon;

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
			$csvData[$key] = trim($data[$i]);
		}
	
		return $csvData;
	}
	
	/**
	 * Get db date from format
	 * @param string $date
	 * @param string $sourceFormat
	 * @return string|null
	 */
	protected function getDbDateFromFormat($date, $sourceFormat = 'm/d/Y')
	{
        if ($date == '') {
            return null;
        }
        
        return Carbon::createFromFormat($sourceFormat, $date)->format('Y-m-d');
	}
	
	/**
	 * Get roster db data
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $businessName
	 * @param string $address1
	 * @param string $address2
	 * @param string $city
	 * @param string $county
	 * @param string $state
	 * @param string $zip
	 * @param string $licenseNo
	 * @param string|null $licenseEffectiveDate
	 * @param string|null $licenseExpirationDate
	 * @param string $licenseStatus
	 * @param string $licenseStatusReason
	 */
	protected function getRosterDbData(
	    $firstName,
	    $lastName,
	    $businessName,
	    $address1,
	    $address2,
	    $city,
	    $county,
	    $state,
	    $zip,
	    $licenseNo,
	    $licenseEffectiveDate,
	    $licenseExpirationDate,
	    $licenseStatus,
	    $licenseStatusReason
	) {
	    
		return [
		    'first_name' => $firstName,
		    'last_name' => $lastName,
		    'business_name' => $businessName,
		    'address1' => $address1,
		    'address2' => $address2,
		    'city' => $city,
		    'county' => $county,
		    'state' => $state,
		    'zip' => $zip,
		    'license_no' => $licenseNo,
		    'license_effective_date' => $licenseEffectiveDate,
		    'license_expiration_date' => $licenseExpirationDate,
		    'license_status' => $licenseStatus,
		    'license_status_reason' => $licenseStatusReason
		];
	}
}