<?php namespace App\Import\Lists;


class Montana extends ExclusionList{



    public $dbPrefix = 'mt1';


    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/montana/mt.xlsx';


    public $type = 'xlsx';


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $fieldNames = [
        'provider_name',
        'provider_type',
        'exclusion_termination_date',
        'exclusion_termination_agency',
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'provider_name',
        'provider_type',
        'exclusion_termination_date'
    ];


    /**
     * @var array
     */
    public $dateColumns = [
        'exclusion_termination_date' => 2,
    ];

}