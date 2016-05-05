<?php namespace App\Import\Lists;

class SouthCarolina extends ExclusionList
{
    public $dbPrefix = 'sc1';

    public $uri = 'https://www.scdhhs.gov/sites/default/files/Exclusion%20Provider%20List%20for%20DHHS%20Website_14.xls';

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

    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
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
    	$providerNo = preg_replace($this->npiRegex, "", trim($row[1]));
    	
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
    	preg_match_all($this->npiRegex, $row[1], $npi);
    
    	$row[1] = $npi[0];
    	 
    	return $row;
    }
}
