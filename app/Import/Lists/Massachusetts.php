<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Massachusetts extends ExclusionList
{
    public $dbPrefix = 'ma1';

    public $uri = 'http://www.mass.gov/eohhs/docs/masshealth/provlibrary/suspended-excluded-masshealth-providers.xls';

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'provider_name',
        'provider_type',
        'npi',
        'reason',
        'effective_date'
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
    	    
    	    // set npi number array
    	    $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
    	    	
    	    $data[] = $row;
    	}
    	 
    	// set back to global data
    	$this->data = $data;
    }
    
}
