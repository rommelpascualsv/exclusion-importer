<?php namespace App\Import\Lists;

class NorthCarolina extends ExclusionList
{
    public $dbPrefix = 'nc1';

	public $uri = 'http://www2.ncdhhs.gov/dma/ProgramIntegrity/ProviderExclusionList_082615.xlsx';

    public $type = 'xlsx';

    public $retrieveOptions = [
        'headerRow' => 1,
        'offset' => 1
    ];

    public $fieldNames = [
        'npi',
        'last_name',
        'first_name',
        'address_1',
        'city',
        'state',
        'zip',
        'health_plan',
        'provider_type',
        'date_excluded',
        'exclusion_reason'
    ];

    public $hashColumns = [
        'first_name',
        'last_name',
        'npi',
        'date_excluded'
    ];

    public $dateColumns = [
        'date_excluded' => 9
    ];

    public $shouldHashListName = true;
    
    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
	public function preProcess()
    {
    	$this->parse();
        parent::preProcess();
    }
    
    public function parse()
    {
    	//Remove table header
    	array_shift($this->data);
    	
    	$this->data = array_map(function($row) {
    		if (count($row) > 11) {
    			unset($row[11]);
    			return $row;
    		}
    		return $row;
    	}, $this->data);
    	
    	$rows = $this->data;
    		 
    	$data = [];
    	foreach ($rows as $key => $value) {
    		$data[] = $this->handleRow($value);
    	}
    	
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
    	preg_match_all($this->npiRegex, $row[0], $npi);
    
    	$row[0] = $npi[0];
    
    	return $row;
    }
}
