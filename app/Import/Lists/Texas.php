<?php

namespace App\Import\Lists;

class Texas extends ExclusionList
{
    public $dbPrefix = 'tx1';

    public $uri = '/vagrant/storage/app/tx1.xls';

    public $type = 'xls';

    public $shouldHashListName = true;

    public $fieldNames = [
        'company_name',
        'last_name',
        'first_name',
        'mid_initial',
        'occupation',
        'license_number',
        'npi',
        'start_date',
        'add_date',
        'reinstated_date',
        'web_comments'
    ];

    public $hashColumns = [
        'company_name',
        'last_name',
        'first_name',
        'mid_initial',
        'npi',
        'start_date',
        'reinstated_date'
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $dateColumns = [
        'start_date' => 7,
        'add_date' => 8,
        'reinstated_date' => 9,
    ];
    
    public $shouldHashListName = true;
    
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
    	preg_match_all($this->npiRegex, $row[6], $npi);
    
    	$row[6] = $npi[0];
    
    	return $row;
    }
}
