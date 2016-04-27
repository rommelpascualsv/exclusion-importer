<?php namespace App\Import\Lists;

class Maryland extends ExclusionList
{
    public $dbPrefix = 'md1';

    public $uri = "http://dhmh.maryland.gov/oig/Documents/03_17_16_Exclusion_Update.xls";

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'last_name',
        'first_name',
        'specialty',
        'sanction_type',
        'sanction_date',
        'address',
        'city_state_zip'
    ];

    public $dateColumns = [
    	'sanction_date' => 4
    ];
}
