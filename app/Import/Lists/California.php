<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class California extends ExclusionList
{
    public $dbPrefix = 'ca1';

    public $uri = 'https://files.medi-cal.ca.gov/pubsdoco/Publications/masters-MTP/zOnlineOnly/susp100-49_z03/suspall_092015.xls';

    public $type = 'xls';

    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_name',
        'aka_dba',
        'addresses',
        'provider_type',
        'license_numbers',
        'provider_numbers',
        'date_of_suspension',
        'active_period',
        'npi'
    ];
    
    public $shouldHashListName = true;
    
    protected $npiColumnName = "npi";
    
    protected $providerNumberColumnName = "provider_numbers";
    
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
    	    
    	    $providerNoIndex = $this->getProviderNumberColumnIndex();
    	    
    	    // set npi number array
    	    $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $providerNoIndex));
    	    
    	    // set provider number
    	    $row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $providerNoIndex), $providerNoIndex);
    	    
    		$data[] = $row;
    	}
    	
    	// set back to global data
    	$this->data = $data;
    }
}
