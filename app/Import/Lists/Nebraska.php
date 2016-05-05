<?php namespace App\Import\Lists;

class Nebraska extends ExclusionList
{
    public $dbPrefix = 'ne1';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p all -u -g -r";

    public $uri = "http://dhhs.ne.gov/medicaid/Documents/Excluded-Providers.pdf";
    
    public $type = 'pdf';

    public $fieldNames = [
        'provider_name',
    	'npi',
        'provider_type',
        'termination_or_suspension',
    	'effective_date',
    	'term',
    	'end_date',
    	'reason_for_action',
    	'provider_number'
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 1
    ];


    public $hashColumns = [
        'provider_name',
        'npi',
    	'effective_date',
    	'term',
    	'end_date'
    ];


    public $dateColumns = [
        'effective_date' => 4
    ];
    
    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $commaRegex = "/^((,|\/)+\s)?(,|\/)?|((,|\/)+\s)?(,|\/)?$/";
    
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
    public function parse()
    {
        $rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
        
        // remove header
        array_shift($rows);
        
        $data = [];
        foreach ($rows as $key => $value) {
        	
        	// do not include if row is empty
        	if (empty($value)) {
        		continue;
        	}
        	
        	// convert string row to comma-delimited array
        	$columns = str_getcsv($value);
        	
        	//remove date_added column
			array_shift($columns);
			
			// applies specific overrides
			$columns = $this->applyOverrides($columns);
			
			// populate the array data
        	$data[] = $columns;
        }

        $this->data = $data;
    }
    
    /**
     * Applies the specific overrides to correct the data
     * 
     * @param array $columns the column array
     * @return array $columns the column array
     */
    private function applyOverrides($columns)
    {
    	// set provider number
    	$columns = $this->setProviderNo($columns);
    	
    	// set npi number array
    	$columns = $this->setNpi($columns);
    	
    	return $columns;
    }
    
    /**
	 * Set the provider number by clearing the unnecessary characters
	 *
	 * @param array $columns the column array
     * @return array $columns the column array
	 */
	private function setProviderNo($columns)
	{
		// remove valid npi numbers
		$providerNo = preg_replace($this->npiRegex, "", trim($columns[1]));
		 
		// remove commas
		$providerNo = preg_replace($this->commaRegex, "", trim($providerNo));
		 
		// remove duplicate spaces in between numbers
		$columns[] = preg_replace($this->spacesRegex, " ", trim($providerNo));
		 
		return $columns;
	}
	
	/**
	 * Set the npi numbers
	 *
	 * @param array $columns the column array
     * @return array $columns the column array
	 */
	private function setNpi($columns)
	{
		// extract npi number/s
		preg_match_all($this->npiRegex, $columns[1], $npi);
	
		$columns[1] = $npi[0];
		 
		return $columns;
	}
}
