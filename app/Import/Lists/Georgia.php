<?php namespace App\Import\Lists;


class Georgia extends ExclusionList
{

    public $dbPrefix = 'ga1';


    public $uri = "https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/georgia/Georgia.xlsx";


    public $fieldNames = [
        'last_name',
        'first_name',
        'business_name',
        'general',
        'state',
        'sanction_date'
    ];


    public $hashColumns = [
        'last_name',
        'first_name',
        'business_name',
        'sanction_date'
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $dateColumns = [
        'sanction_date' => 5
    ];

}
