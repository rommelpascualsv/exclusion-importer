<?php namespace App\Import\Lists;


class Arizona extends ExclusionList
{

    public $dbPrefix = 'az1';

    public $uri = "https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/arizona/azlist.xlsx";

    public $type = 'xlsx';

    public $retrieveOptions = array(
        'headerRow' => 0,
        'offset' => 1
    );

    public $fieldNames = array(
        'first_name',
        'middle',
        'last_name_company_name',
        'term_date',
        'specialty',
        'npi_number'
    );
}