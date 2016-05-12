<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use Carbon\Carbon;

abstract class BaseMapper implements MapperInterface
{
    protected static $dbColumns = [
        'first_name',
        'last_name',
        'business_name',
        'address1',
        'address2',
        'city',
        'county',
        'state',
        'zip',
        'license_no',
        'license_effective_date',
        'license_expiration_date',
        'license_status',
        'license_status_reason'
    ];
    
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
	 * @param array $csvHeaders
	 */
	public function __construct(array $csvHeaders = [])
	{
	    $this->csvHeaders = $csvHeaders;
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
	 * Get date from format
	 * @param string $date
	 * @param string $sourceFormat
	 * @return string|null
	 */
	protected function getDateFromFormat($date, $sourceFormat = 'm/d/Y')
	{
	    if ($date != '') {
	        $date = Carbon::createFromFormat($sourceFormat, $date)->format('Y-m-d');
	    }
	    
	    return $date;
	}
	
	/**
	 * Get roster db data
	 * @param array $data
	 * @return array
	 */
	protected function getRosterDbData(array $data)
	{
	    $dbData = [];
	    
	    foreach (static::$dbColumns as $column) {
	        if (array_key_exists($column, $data) && $data[$column] !== '') {
	            $value = $data[$column];
	        } else {
	            $value = null;
	        }
	        
	        $dbData[$column] = $value;
	    } 
		
		return $dbData;
	}
}