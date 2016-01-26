<?php namespace App\Import\Lists;


class Iowa extends ExclusionList
{

    public $dbPrefix = 'ia1';


    public $uri = "https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/iowa/ia.csv";


    public $type = 'csv';


    public $fieldNames = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction',
        'sanction_end_date'
    ];


    public $hashColumns = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction_end_date'
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $dateColumns = [
        'sanction_start_date' => 0,
        'sanction_end_date' => 6
    ];

}
