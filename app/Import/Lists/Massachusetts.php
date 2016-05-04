<?php namespace App\Import\Lists;

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
    
    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
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
    	preg_match_all($this->npiRegex, $row[2], $npi);
    
    	$row[2] = $npi[0];
    	 
    	return $row;
    }
}
