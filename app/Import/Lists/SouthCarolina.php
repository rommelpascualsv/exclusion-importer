<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class SouthCarolina extends ExclusionList
{
    public $dbPrefix = 'sc1';
                  
    public $uri = 'https://www.scdhhs.gov/sites/default/files/Exclusion%20Provider%20List%20for%20DHHS%20Website_19.xls';

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 2,
        'offset' => 4
    ];

    public $hashColumns = [
        'entity',
        'npi',
        'city',
        'state',
        'zip',
        'date_excluded'
    ];

    public $fieldNames = [
        'entity',
        'npi',
        'city',
        'state',
        'zip',
        'provider_type',
        'date_excluded',
        'provider_number'
    ];

    public $dateColumns = [
        'date_excluded' => 6
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
    	    	
    	    // populate the array data
    	    $data[] = $row;
    	}
    	 
    	// set back to global data
    	$this->data = $data;
    }
}
