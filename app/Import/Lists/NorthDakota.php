<?php namespace App\Import\Lists;

class NorthDakota extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'nd1';

    /**
     * @var string
     */
    public $uri = 'http://www.nd.gov/dhs/services/medicalserv/medicaid/docs/pro-exclusion-list.xlsx';

    /**
     * @var string
     */
    public $type = 'xlsx';

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'provider_name',
        'provider_verification',
        'business_name_address',
        'medicaid_provider_id',
        'medicaid_provider_number',
        'npi',
        'provider_type',
        'state',
        'exclusion_date',
        'exclusion_reason',
        'exclusion_reason_2',
        'medicaid_provider_number_2',
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'provider_name',
        'business_name_address',
        'medicaid_provider_id',
        'medicaid_provider_number',
        'npi',
        'exclusion_date'
    ];

    /**
     * @var array
     */
    public $dateColumns = [];

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
    	preg_match_all($this->npiRegex, $row[5], $npi);
    
    	$row[5] = $npi[0];
    		
    	return $row;
    }
    
    public function postHook()
    {
        $results = app('db')
            ->table($this->dbPrefix . '_records')
            ->select('id', 'business_name_address')
            ->get();

        foreach ($results as $key => $value) {
            preg_match('/^(?!N\/A)[\D]+/', $value->business_name_address, $match);

            if ( ! empty($match)) {
                app('db')
                    ->table($this->dbPrefix . '_records')
                    ->where('id', $value->id)
                    ->update(['business' => $match[0]]);
            }
        }
    }
}
