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

	public function preProcess()
    {
        parent::preProcess();
        $this->data = array_map(function($row) {
            return array_slice($row, 0, -1);
		}, $this->data);
    }
    
}
