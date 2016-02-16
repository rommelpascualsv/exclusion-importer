<?php namespace App\Import\Lists;


class Georgia extends ExclusionList
{

    public $dbPrefix = 'ga1';


    public $uri = "https://dch.georgia.gov/sites/dch.georgia.gov/files/Georgia%20DCH%20OIG%20Medicaid%20Exclusions%20-02012016.xlsx";


    public $type = 'xlsx';


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
        'headerRow' => 2,
        'offset' => 3
    ];


    public $dateColumns = [
        'sanction_date' => 5
    ];

}
