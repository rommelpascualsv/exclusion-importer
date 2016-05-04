<?php namespace App\Import\Lists;

class NewJersey extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'njcdr';

    public $uri = 'http://www.state.nj.us/treasury/debarment/files/Debarment.txt';

    public $type = 'txt';

    public $hashColumns = [
        'firm_name',
        'name',
        'npi',
        'effective_date',
        'expiration_date',
        'permanent_debarment'
    ];

    public $fieldNames = [
        'firm_name',
        'name',
        'vendor_id',
        'firm_street',
        'firm_city',
        'firm_state',
        'firm_zip',
        'firm_plus4',
        'npi',
        'street',
        'city',
        'state',
        'zip',
        'plus4',
        'category',
        'action',
        'reason',
        'debarring_dept',
        'debarring_agency',
        'effective_date',
        'expiration_date',
        'permanent_debarment',
    	'provider_number'
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'effective_date'  => 19,
        'expiration_date' => 20
    ];

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 0
    ];

    public $shouldHashListName = true;

    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $symbolsRegex = "/^((,|\/)+\s)?(,|\/)?|((,|\/)+\s)?(,|\/)?$/";
    
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
    	// remove underscore from name field
    	$row = $this->normalizeName($row);
    	
    	// set provider number
    	$row = $this->setProviderNo($row);
    	
    	// set npi number array
    	$row = $this->setNpi($row);
    
    	return $row;
    }
    
    /**
     * Normalize the name field by replacing the underscore characters with spaces.
     *
     * @param array $row the array record
     * @return array $row the array record
     */
    private function normalizeName($row)
    {
    	$row[1] = str_replace('_', ' ', $row[1]);
    	
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
    	$providerNo = preg_replace($this->npiRegex, "", trim($row[8]));
    		
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
    	preg_match_all($this->npiRegex, $row[8], $npi);
    
    	$row[8] = $npi[0];
    
    	return $row;
    }
}
