<?php namespace App\Import\Lists;


class Montana extends ExclusionList{



    public $dbPrefix = 'mt1';


    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/montana/mt.xlsx';


    public $retrieveOptions = array(
        'headerRow' => 0,
        'offset' => 1
    );


    public $fieldNames = array(
        'provider_name',
        'provider_type',
        'exclusion_termination_date',
        'exclusion_termination_agency',
    );

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