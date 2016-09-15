<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class California extends ExclusionList
{
    public $dbPrefix = 'ca1';

    public $uri = 'https://files.medi-cal.ca.gov/pubsdoco/Publications/masters-MTP/zOnlineOnly/susp100-49_z03/suspall_032016.xls';

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
        'npi',
        'business'
    ];
    
    public $dateColumns = [
        'date_of_suspension' => 8
    ];
    
    public $shouldHashListName = true;
    
    protected $npiColumnName = "npi";
    
    protected $providerNumberColumnName = "provider_numbers";
    
    private $lastNameColumnIndex;
    private $firstNameColumnIndex;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->lastNameColumnIndex = array_search('last_name', $this->fieldNames);
        $this->firstNameColumnIndex = array_search('first_name', $this->fieldNames);
    }
    
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
    	    
    	    $business = $this->getBusinessFrom($row);
            
       	    $row[] = $business; 	
    	    
    	    if (! empty($business)) {
    	        $row[$this->lastNameColumnIndex] = '';
    	    }
    	    	
    		$data[] = $row;
    	}
    	
    	// set back to global data
    	$this->data = $data;
    }
    
    private function getBusinessFrom($row)
    {
        $lastName = $row[$this->lastNameColumnIndex];
        $firstName = $row[$this->firstNameColumnIndex];
        
        return empty(trim($firstName)) ? trim($lastName) : '';
    }
}
