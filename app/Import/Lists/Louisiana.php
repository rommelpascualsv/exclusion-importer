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


}