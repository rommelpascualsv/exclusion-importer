<?php namespace App\Import\Lists;

class Florida extends ExclusionList
{
    public $dbPrefix = 'fl2';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/florida/FOReport.xls';

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'provider',
        'medicaid_provider_number',
        'license_number',
        'npi_number',
        'provider_type',
        'date_rendered',
        'sanction_type',
        'violation_code',
        'fine_amount',
        'sanction_date',
        'ahca_case_number',
        'formal_informal_case_number',
        'document_type'
    ];

    public $hashColumns = [
        'provider',
        'medicaid_provider_number',
        'license_number',
        'npi_number',
        'date_rendered',
        'sanction_date'
    ];

    public $dateColumns = [
        'date_rendered' => 5,
        'sanction_date' => 9
    ];

    public $shouldHashListName = true;

    public $npiColumnName = "npi_number";
    
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
    	preg_match_all($this->npiRegex, $row[3], $npi);
    
    	$row[3] = $npi[0];
    
    	return $row;
    }
    
    public function postHook()
    {
        app('db')->table('fl2_records')
            ->whereNotIn(app('db')->raw('TRIM(`sanction_type`)'),['' , 'SUSPENSION', 'TERMINATION', 'NONE'])
            ->delete();
    }
}
