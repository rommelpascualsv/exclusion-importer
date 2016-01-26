<?php namespace App\Import\Lists;


class Alaska extends ExclusionList
{

    public $dbPrefix = 'ak1';


    public $uri = "https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/alaska/aklist.xlsx";


    public $type = 'xlsx';


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

}
