<?php namespace App\Import\Lists;


//TODO: unset a bunch of columns

class SouthCarolina extends ExclusionList
{

    public $dbPrefix = 'sc1';


    public $uri = 'https://www.scdhhs.gov/sites/default/files/Exclusion%20Provider%20List%20for%20DHHS%20Website_1.xls';


    public $retrieveOptions = array(
        'headerRow' => 2,
        'offset' => 4
    );


    public $fieldNames = array(
        'entity',
        'npi',
        'city',
        'state',
        'zip',
        'provider_type',
        'date_excluded'
    );
}