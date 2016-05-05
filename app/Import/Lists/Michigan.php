<?php namespace App\Import\Lists;

class Michigan extends ExclusionList
{
    public $dbPrefix = 'mi1';

    public $uri = 'http://www.michigan.gov/documents/mdch/MI_Sanctioned_Provider_List_375503_7.xls';

    public $type = 'xls';

    public $shouldHashListName = true;

    public $retrieveOptions = [
        'headerRow' => 1,
        'offset' => 2
    ];

    public $fieldNames = [
        'entity_name',
        'last_name',
        'first_name',
        'middle_name',
        'provider_category',
        'npi_number',
        'city',
        'license_number',
        'sanction_date_1',
        'sanction_source_1',
        'sanction_date_2',
        'sanction_source_2',
        'reason',
        'provider_number'
    ];

    public $hashColumns = [
        'entity_name',
        'last_name',
        'first_name',
        'middle_name',
        'npi_number',
        'license_number',
        'sanction_date_1',
        'sanction_date_2'
    ];

    public $dateColumns = [
        'sanction_date_1' => 8,
        'sanction_date_2' => 10
    ];
    
    public $npiColumnName = "npi_number";
    
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
