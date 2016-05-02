<?php namespace App\Import\Lists;

class Louisiana extends ExclusionList
{
    public $dbPrefix = 'la1';

    public $uri = 'https://adverseactions.dhh.la.gov/SelSearch/GetCsv';

    public $type = 'csv';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 2
    ];

    public $fieldNames = [
        'first_name',
        'last_or_entity_name',
        'birthdate',
        'affiliated_entity',
        'title_or_type',
        'npi',
        'exclusion_reason',
        'period_of_exclusion',
        'effective_date',
        'reinstate_date',
        'state_zip'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'first_name',
        'last_or_entity_name',
        'birthdate',
        'affiliated_entity',
        'npi',
        'exclusion_reason',
        'effective_date',
    ];

    /**
     * @var array
     */
    public $dateColumns = [
	    'birthdate' => 2,
        'effective_date' => 8,
    ];
    
    public $npiColumn = 5;
    
    /**
     * Retrieves the array string for a given space-delimeted value
     *
     * @param string $value the npi space-delimeted value
     * @return array the array string npi values
     */
    protected function getNpiValues($value)
    {
    	// set null if npi contains letters
    	if (preg_match("/[A-Z]|[a-z]/", $value)) {
    		$value = null;
    	}
    
    	return parent::getNpiValues($value);
    }
}
