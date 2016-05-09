<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Missouri extends ExclusionList
{
	public $dbPrefix = 'mo1';

	public $uri = 'http://www.mmac.mo.gov/files/Sanction-List-jan-2016.xls';

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
	
	public $shouldHashListName = true;
	
	protected $npiColumnName = "npi";
	
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
			
		    $npiColumnIndex = $this->getNpiColumnIndex();
		    	
		    // set provider number
		    $row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $npiColumnIndex));
		    
		    // set npi number array
		    $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
		    
		    $data[] = $row;
		}
		 
		// set back to global data
		$this->data = $data;
	}
}
