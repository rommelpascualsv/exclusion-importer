<?php namespace App\Import\Lists;

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
    
    public $npiColumnName = "npi";
    
    public $npiRegex = "/1\d{9}/";
    
    public $commaRegex = "/^(,+\s)?,?|(,+\s)?,?$/";
    
    public $spacesRegex = "!\s+!";
    
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
    	// set npi number array
    	$row = $this->setNpi($row);
    	
    	// set provider number
    	$row = $this->setProviderNo($row);
    	
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
    	$row[7] = preg_replace($this->npiRegex, "", trim($row[7]));
    	
    	// remove commas
    	$row[7] = preg_replace($this->commaRegex, "", trim($row[7]));
    	
    	// remove duplicate spaces in between numbers
    	$row[7] = preg_replace($this->spacesRegex, " ", trim($row[7]));
    	
    	return $row;
    }
    
    /**
     * Set the npi numbers by extracting from provider number column
     *   
     * @param array $row the array record
     * @return array $row the array record
     */
    private function setNpi($row)
    {
    	// extract npi number/s
    	preg_match_all($this->npiRegex, $row[7], $npi);
    	 
    	$row[] = $npi[0];
    	
    	return $row;
    }
}
