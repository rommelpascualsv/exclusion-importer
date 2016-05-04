<?php namespace App\Import\Lists;

class Kansas extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'ks1';

    /**
     * @var string
     */
    public $uri = 'http://www.kdheks.gov/hcf/medicaid_program_integrity/download/Termination_List.pdf';
    
    public $pdfToText = "java -jar ../etc/tabula.jar -p all";

    /**
     * @var string
     */
    public $type = 'pdf';

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
        'termination_date',
        'name',
        'd_b_a',
        'provider_type',
        'kmap_provider_number',
        'npi',
        'comments',
    	'kmap_provider_number_2'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'termination_date',
        'name',
        'd_b_a',
        'npi',
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'termination_date' => 0
    ];
    
    /**
     * @var contains the headers of the pdf that should be excluded
     */
    private $headers = [
    		'"",,,,,,,"REVISED March 25, 2016"',
    		'"Termination ",,,,,,,',
    		'"Date: ","Name: ","d/b/a: ","Provider Type: ","KMAP Provider #: ","NPI #: ",Comments:,'
    ];
    
    public $npiColumnName = "npi";
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $symbolsRegex = "/^(;+\s)?;?|(;+\s)?;?$/";
    
    private $spacesRegex = "!\s+!";
    
    public function preProcess()
    {
    	$this->parse();
    	parent::preProcess();
    }
    
    public function parse()
    {
    	// remove all headers
    	$this->data = str_replace($this->headers, "", $this->data);
    	
    	// split into rows
    	$rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
    
    	$data = [];
    	$mergeData = [];
    	foreach ($rows as $key => $value) {
    		 
    		// do not include if row is empty or contains page number
    		if (empty($value)) {
    			continue;
    		}
    		 
    		// convert string row to comma-delimited array
    		$columns = str_getcsv($value);
    		
    		// remove excess column
    		array_pop($columns);
    		 
    		// checks if termination_date if empty then this record belongs to another record
    		if (empty($columns[0])) {
    			$mergeData = $this->buildMergeData($mergeData, $columns);
    			continue;
    		}
    		 
    		// combine rows that belongs together
    		if (!empty($mergeData)) {
    			$columns = $this->buildColumnsData($mergeData, $columns);
    		}
    		
    		// cleans the records
    		$columns = array_map('trim', $columns);

    		// populate the array data
    		$data[] = $this->handleRow($columns);
    		 
    		// resets $mergeData
    		$mergeData = [];
    	}
    
    	$this->data = $data;
    }
    
    /**
     * Builds the column records by merging the merge data
     *
     * @param $mergeData the merge data
     * @param $columns the current column record
     * @return $columns the current column record
     *
     */
    private function buildColumnsData($mergeData, $columns)
    {
    	foreach ($mergeData as $k => $v) {
    		$columns[$k] = $v . " " . $columns[$k];
    		$columns[$k] = preg_replace('!\s+!', ' ', $columns[$k]);
    	}
    	return $columns;
    }
    
    /**
     * Builds the merge data needed for merging related rows
     *
     * @param $mergeData the merge data
     * @param $columns the current column record
     * @return $columns the current column record
     */
    private function buildMergeData($mergeData, $columns)
    {
    	if (empty($mergeData) || empty(trim(implode('', $mergeData)))) {
    		$mergeData = $columns;
    	} else {
    		foreach ($columns as $k => $v) {
    			$mergeData[$k] = $mergeData[$k] . " " . $v;
    		}
    	}
    	return $mergeData;
    }
    
    /**
     * Handles the data manipulation of a record array.
     *
     * @param array $columns the array record
     * @return array $columns the array record
     */
    private function handleRow($columns)
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
     * @param array $columns the array record
     * @return array $columns the array record
     */
    private function setProviderNo($columns)
    {
    	// remove valid npi numbers
    	$providerNo = preg_replace($this->npiRegex, "", trim($columns[5]));
    
    	// remove commas
    	$providerNo = preg_replace($this->symbolsRegex, "", trim($providerNo));
    
    	// remove duplicate spaces in between numbers
    	$columns[] = preg_replace($this->spacesRegex, " ", trim($providerNo));
    
    	return $columns;
    }
    
    /**
     * Set the npi numbers
     *
     * @param array $columns the array record
     * @return array $columns the array record
     */
    private function setNpi($columns)
    {
    	// extract npi number/s
    	preg_match_all($this->npiRegex, $columns[5], $npi);
    
    	$columns[5] = $npi[0];
    
    	return $columns;
    }
}
