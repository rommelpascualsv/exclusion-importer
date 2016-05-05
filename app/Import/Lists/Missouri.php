<?php namespace App\Import\Lists;

class Missouri extends ExclusionList
{
	public $dbPrefix = 'mo1';

	public $uri = 'http://www.mmac.mo.gov/files/Sanction-List-10-15-upd.xls';

	public $type = 'xls';

	public $hashColumns = [
        'provider_name',
		'termination_date',
		'npi'
	];

	public $fieldNames = [
        'termination_date',
        'letter_date',
        'provider_name',
        'npi',
        'provider_type',
        'license_number',
        'termination_reason',
        'provider_number'
	];

	public $dateColumns = [
		'termination_date' => 0,
		'letter_date' => 1
	];
	
	public $npiColumnName = "npi";
	
	private $npiRegex = "/1\d{9}\b/";
	
	private $symbolsRegex = "/^((,|\/)+\s)?(,|\/)?|((,|\/)+\s)?(,|\/)?$/";
	
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
		$providerNo = preg_replace($this->npiRegex, "", trim($row[3]));
		 
		// remove commas
		$providerNo = preg_replace($this->symbolsRegex, "", trim($providerNo));
		 
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
		preg_match_all($this->npiRegex, $row[3], $npi);
	
		$row[3] = $npi[0];
		 
		return $row;
	}
}
