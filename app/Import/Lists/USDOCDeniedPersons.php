<?php namespace App\Import\Lists;


class USDOCDeniedPersons extends ExclusionList {

    public $dbPrefix = 'usdocdp';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/usdocdp/DPL_2015-11-09.csv';

    public $type = 'csv';

    public $dateColumns = [
        'effective_date' => 6,
        'expiration_date' => 7,
        'last_update' => 8,
    ];

    public $retrieveOptions = [
        'headRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'name',
        'street_address',
        'city',
        'state',
        'country',
        'postal_code',
        'effective_date',
        'expiration_date',
        'last_update'
    ];

    public $hashColumns = [
        'name',
        'city',
        'state',
        'country',
        'effective_date',
        'expiration_date'
    ];

}
