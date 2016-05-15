<?php namespace App\Import\Lists;

class Alaska extends ExclusionList
{
    public $dbPrefix = 'ak1';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar /vagrant/etc/tabula.jar -p 2-7 -c 89,215,308,546,598,753";

    public $uri = "http://dhss.alaska.gov/Commissioner/Documents/PDF/AlaskaExcludedProviderList.pdf";
    
    public $type = 'pdf';

    public $fieldNames = [
        'exclusion_date',
        'last_name',
        'first_name',
        'middle_name',
        'provider_type',
        'exclusion_authority',
        'exclusion_reason'
    ];

    public $hashColumns = [
        'exclusion_date',
        'last_name',
        'first_name',
        'middle_name',
        'exclusion_authority',
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $dateColumns = [
        'exclusion_date' => 0
    ];

    private $headers = [
    		'"",,Alas,ka Medical Assistance Excluded Provider List,,',
    		'"",,,May 2016,,',
    		'"EXCLUSION ",,,,"EXCLUSION ",',
    		'"",LAST NAME,FIRST NAME,PROVIDER TYPE,,EXCLUSION REASON',
    		'DATE,,,,AUTHORITY,'
    ];
    
    public function preProcess()
    {
    	$this->parse();
    	parent::preProcess();
    }
    
    public function parse()
    {
    	// remove all headers
    	$this->data = str_replace($this->headers, "", $this->data);
    	
    	// remove all page numbers
    	$this->data = preg_replace('/"",,,Page \\d of \\d,,/', "", $this->data);
    	
    	$rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
    	
    	$data = [];
    	foreach ($rows as $key => $value) {
    		 
    		// do not include if row is empty
    		if (empty($value) || $this->isHeader($value)) {
    			continue;
    		}
    		
    		// convert string row to comma-delimited array
    		$columns = str_getcsv($value);
    		
    		// applies specific overrides
    		$columns = $this->applyOverrides($columns);
    		
    		// populate the array data
    		array_push($data, $columns);
    	}
    
    	$this->data = $data;
    }
    
    /**
     * Applies the specific overrides to correct the data
     * @param array $columns the column array
     * @return array $columns the column array
     */
    private function applyOverrides($columns)
    {
    	$columns = $this->populateFirstMiddleName($columns);
    	
    	return $columns;
    }
    
    /**
     * Populates the first and middle name columns
     * @param array $columns the column array
     * @return array $columns the column array
     */
    private function populateFirstMiddleName($columns)
    {
    	$firstMiddle = explode(" ", $columns[2], 2);
    	 
    	if (count($firstMiddle) == 1) {
    		$firstMiddle[1] = "";
    	}
    	 
    	array_splice($columns, 2, 1, $firstMiddle);
    	 
    	return $columns;
    }
    
    /**
     * Determines if the value is a header.
     * @param string $value the string row value
     * @return boolean returns true if value is header, otherwise false.
     */
    private function isHeader($value)
    {
    	
    	$value = str_replace("\r", "", $value);
    	if ('"EXCLUSION DATE",LAST NAME,FIRST NAME,PROVIDER TYPE,"EXCLUSION AUTHORITY",EXCLUSION REASON' === $value) {
    		return true;
    	}
    	
    	return false;
    }
}
