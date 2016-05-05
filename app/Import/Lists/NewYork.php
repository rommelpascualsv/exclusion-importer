<?php namespace App\Import\Lists;

class NewYork extends ExclusionList
{
	public $dbPrefix = 'nyomig';

	public $uri = 'http://www.omig.ny.gov/data/gensplistns.php';

	public $type = 'txt';

	public $retrieveOptions = [
		'headerRow' => 0,
		'offset' => 1
	];

	public $fieldNames = [
		'business',
		'provider_number',
		'npi',
		'provtype',
		'action_date',
		'action_type',
		'provider_number_2'
	];

	public $hashColumns = [
		'business',
		'provider_number',
		'npi',
		'action_date'
	];
	
	public $shouldHashListName = true;
	
	public $npiColumnName = "npi";
	
	private $npiRegex = "/1\d{9}\b/";
	
	private $commaRegex = "/^((,|\/)+\s)?(,|\/)?|((,|\/)+\s)?(,|\/)?$/";
	
	private $spacesRegex = "!\s+!";
	
	/**
	 * @inherit preProcess
	 */
	public function preProcess()
	{
		$this->parse();
		parent::preProcess();
	}
	
	/**
	 * Parse the input data
	 */
	private function parse()
	{
		$data = [];
			
		// iterate each row
		foreach ($this->data as $row) {
			$data[] = $this->handleRow($row);
		}
			
		// set back to global data
		$this->data = $data;
	}
	
	/**
	 * Handles the data manipulation of a record array.
	 *
	 * @param array $row the array record
	 * @return array $row the array record
	 */
	private function handleRow($row)
	{
			
		// set provider number
		$row = $this->setProviderNo($row);
	
		// set npi number array
		$row = $this->setNpi($row);
			
		return $row;
	}
	
	/**
	 * Set the provider number by clearing the unnecessary characters
	 *
	 * @param array $row the array record
	 * @return array $row the array record
	 */
	private function setProviderNo($row)
	{
		// remove valid npi numbers
		$providerNo = preg_replace($this->npiRegex, "", trim($row[2]));
			
		// remove commas
		$providerNo = preg_replace($this->commaRegex, "", trim($providerNo));
			
		// remove duplicate spaces in between numbers
		$row[] = preg_replace($this->spacesRegex, " ", trim($providerNo));
			
		return $row;
	}
	
	/**
	 * Set the npi numbers
	 *
	 * @param array $row the array record
	 * @return array $row the array record
	 */
	private function setNpi($row)
	{
		// extract npi number/s
		preg_match_all($this->npiRegex, $row[2], $npi);
	
		$row[2] = $npi[0];
			
		return $row;
	}
}
