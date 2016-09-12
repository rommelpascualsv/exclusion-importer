<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class NewJersey extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'njcdr';

    public $uri = 'http://www.state.nj.us/treasury/treasfiles/debarment/Debarment.txt';

    public $type = 'txt';

    public $hashColumns = [
        'firm_name',
        'name',
        'firm_street',
        'firm_city',
        'firm_state',
        'firm_zip',
        'npi',
        'street',
        'city',
        'state',
        'zip',
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

    protected $npiColumnName = "npi";

    protected $zipCodeLength = 5;

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

        $row = $this->normalizeZipCodes($row, ['firm_zip', 'zip']);

    	$npiColumnIndex = $this->getNpiColumnIndex();
			 
		// set provider number
		$row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $npiColumnIndex));
		
		// set npi number array
		$row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);

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
    
    private function normalizeZipCodes($row, $zipCodeFieldNames)
    {
        foreach ($zipCodeFieldNames as $zipCodeFieldName) {

            $zipCodeFieldIndex = array_search($zipCodeFieldName, $this->fieldNames);

            if ($zipCodeFieldIndex === false) {
                continue;
            }

            $zipCode = trim($row[$zipCodeFieldIndex]);

            $row[$zipCodeFieldIndex] = empty($zipCode) ? $zipCode : str_pad($zipCode, $this->zipCodeLength, '0', STR_PAD_LEFT);
        }

        return $row;
    }
}
