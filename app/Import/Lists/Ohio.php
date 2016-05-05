<?php namespace App\Import\Lists;

class Ohio extends ExclusionList
{
    public $dbPrefix = 'oh1';

    public $uri = 'http://medicaid.ohio.gov/Portals/0/Providers/Enrollment%20and%20Support/ExclusionSuspensionList.xlsx';

    public $type = 'xlsx';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'last_name',
        'first_name',
        'organization_name',
        'date_of_birth',
        'npi',
        'address1',
        'address2',
        'city',
        'state',
        'zip_code',
        'provider_id',
        'status',
        'action_date',
        'date_added',
        'provider_type',
        'date_revised',
        'provider_number'
    ];

    public $hashColumns = [

        'last_name',
        'first_name',
        'organization_name',
        'date_of_birth',
        'npi',
        'provider_id',
        'status',
        'action_date'
    ];

    public $dateColumns = [

        'date_of_birth' => 3,
        'action_date' => 12,
        'date_added' => 13,
        'date_revised' => 15,
    ];
    
    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $symbolsRegex = "/^((,|\/|;)+\s)?(,|\/|;)?|((,|\/|;)+\s)?(,|\/|;)?$/";
    
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
    	$providerNo = preg_replace($this->npiRegex, "", trim($row[4]));
    	 
    	// remove commas
    	$providerNo = preg_replace($this->symbolsRegex, "", trim($providerNo));
    	 
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
    	preg_match_all($this->npiRegex, $row[4], $npi);
    
    	$row[4] = $npi[0];
    	 
    	return $row;
    }
}
