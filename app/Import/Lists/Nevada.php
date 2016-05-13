<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Nevada extends ExclusionList
{
    public $dbPrefix = 'nv1';

    public $pdfToText = "java -jar ../etc/tabula.jar -p all -c 126,220,305,350,400,440,495,532,580,635,680";

    public $uri = "http://dhcfp.nv.gov/uploadedFiles/dhcfpnvgov/content/Providers/PI/NevadaProviderExclusions.pdf";
    
    public $type = 'pdf';
    
    public $fieldNames = [
    	'doing_business_as',
    	'legal_entity',
    	'ownership_of_at_least_5_percent',
    	'medicaid_provider',
    	'npi',
    	'provider_type',	
    	'termination_date',
    	'sanction_tier',
    	'sanction_period',
    	'sanction_period_end_date',
    	'reinstatement_date'
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 1
    ];
    
    public $hashColumns = [
    	'doing_business_as',
    	'legal_entity', 
    	'npi',
    	'termination_date',
    	'sanction_period',
    	'sanction_period_end_date',
    	'reinstatement_date'
    ];

    public $dateColumns = [
    	'termination_date' => 6,
    	'sanction_period_end_date' => 9,
    	'reinstatement_date' => 10
    ];
    
    public $shouldHashListName = true;

    protected $npiColumnName = "npi";
    
    /**
     * @var contains the headers of the pdf that should be excluded
     */
    private $headers = [
    	',,,,,,,,,,Mar 2016',
    	',,,,,,,,,"NV ",',
    	',,,,,,,,,"Medicaid ",',
    	',,"Persons with ",,,,,,,"Sanction ","Federal "',
    	',,"controlling inerest of ","Medicaid ",,"Provider ","Termination ","Sanction ","Sanction ","Period End ","Reinstate "',
    	'Business Name,Legal Entity,5% or more,Provider,NPI,Type,Date,Tier,Period,Date,Date'
    ];
    
    /**
     * @var contains the legal entities that need to be manually corrected
     */
    private $legalEntity = [
    	'Helping Others Help Themsel,ves' => 'Helping Others Help Themselves,'
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
    	$this->data = preg_replace('/"",,,,"Page \\d ",of \\d,,,,,/', "", $this->data);
    	
    	// correct the converted data 
    	$this->data = str_replace(array_keys($this->legalEntity), array_values($this->legalEntity), $this->data);
    	
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
        	
        	// checks if termination_date if empty then this record belongs to another record
        	if (empty($columns[6])) {
        		$mergeData = $this->buildMergeData($mergeData, $columns);
        		continue;
        	}
        	
        	// combine rows that belongs together
        	if (!empty($mergeData)) {
        		$columns = $this->buildColumnsData($mergeData, $columns);
        	}
        	
        	// set npi number array
        	$npiColumnIndex = $this->getNpiColumnIndex();
		    $columns = PNHelper::setNpiValue($columns, PNHelper::getNpiValue($columns, $npiColumnIndex), $npiColumnIndex);
        	
			// populate the array data
        	$data[] = $columns;
        	
        	// resets $mergeData
        	$mergeData = [];
        }

        $this->data = $data;
    }
    
    /**
     * Builds the column records by merging the merge data
     *
     * @param mergeData the merge data
     * @param columns the current column record
     * @return columns the current column record
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
	 * @param mergeData the merge data
	 * @param columns the current column record
	 * @return columns the current column record
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
}