<?php namespace App\Import\Lists;

class Nebraska extends ExclusionList
{
    public $dbPrefix = 'ne1';

    public $pdfToText = "java -jar ../etc/tabula.jar -p all -u -g -r";

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
        'reason_for_action'
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

    public function preProcess()
    {
    	$this->parse();
    	parent::preProcess();
    }

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
			
			// populate the array data
        	$data[] = $columns;
        }

        $this->data = $data;
    }
}
