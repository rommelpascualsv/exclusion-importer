<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Louisiana extends ExclusionList
{
    public $dbPrefix = 'la1';

    public $uri = 'https://adverseactions.dhh.la.gov/SelSearch/GetCsv';

    public $type = 'csv';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 2
    ];

    public $fieldNames = [
        'first_name',
        'last_or_entity_name',
        'birthdate',
        'affiliated_entity',
        'title_or_type',
        'npi',
        'exclusion_reason',
        'period_of_exclusion',
        'effective_date',
        'reinstate_date',
        'state_zip',
        'provider_number'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'first_name',
        'last_or_entity_name',
        'birthdate',
        'affiliated_entity',
        'npi',
        'exclusion_reason',
        'effective_date',
    ];

    /**
     * @var array
     */
    public $dateColumns = [
	    'birthdate' => 2,
        'effective_date' => 8,
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
