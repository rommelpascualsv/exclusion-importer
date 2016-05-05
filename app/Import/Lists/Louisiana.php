<?php namespace App\Import\Lists;

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
    
    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $commaRegex = "/^(,+\s)?,?|(,+\s)?,?$/";
    
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
    	$providerNo = preg_replace($this->npiRegex, "", trim($row[5]));
    	
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
    	preg_match_all($this->npiRegex, $row[5], $npi);
    	 
    	$row[5] = $npi[0];
    	
    	return $row;
    }
}
