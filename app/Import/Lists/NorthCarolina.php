<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class NorthCarolina extends ExclusionList
{
    public $dbPrefix = 'nc1';

	public $uri = 'http://www2.ncdhhs.gov/dma/ProgramIntegrity/ProviderExclusionList_082615.xlsx';

    public $type = 'xlsx';

    public $retrieveOptions = [
        'headerRow' => 1,
        'offset' => 1
    ];

    public $fieldNames = [
        'npi',
        'last_name',
        'first_name',
        'address_1',
        'city',
        'state',
        'zip',
        'health_plan',
        'provider_type',
        'date_excluded',
        'exclusion_reason'
    ];

    public $hashColumns = [
        'first_name',
        'last_name',
        'address_1',
        'city',
        'state',
        'zip',
        'npi',
        'date_excluded'
    ];

    public $dateColumns = [
        'date_excluded' => 9
    ];

    public $shouldHashListName = true;
    
    protected $npiColumnName = "npi";
    
	public function preProcess()
    {
    	$this->parse();
        parent::preProcess();
    }
    
    public function parse()
    {
    	//Remove table header
    	array_shift($this->data);
    	
    	$this->data = array_map(function($row) {
    		if (count($row) > 11) {
    			unset($row[11]);
    			return $row;
    		}
    		return $row;
    	}, $this->data);
    	
    	$rows = $this->data;
    		 
    	$data = [];
    	foreach ($rows as $key => $value) {
			 
			// set npi number array
    		$npiColumnIndex = $this->getNpiColumnIndex();
			$value = PNHelper::setNpiValue($value, PNHelper::getNpiValue($value, $npiColumnIndex), $npiColumnIndex);
			
			// populate the array data
        	$data[] = $value;
    	}
    	
    	$this->data = $data;
    }
}
