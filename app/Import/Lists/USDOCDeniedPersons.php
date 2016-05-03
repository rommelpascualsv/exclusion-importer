<?php namespace App\Import\Lists;

class USDOCDeniedPersons extends ExclusionList
{
    public $dbPrefix = 'usdocdp';

    public $uri = 'http://www.bis.doc.gov/dpl/dpl.txt';

    public $type = 'tsv';

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

    /**
     * Columns not included in schema
     *
     * @var array
     */
    public $ignoreColumns = [
        'standard_order' => 8,
        'action' => 10,
        'FR_Citation' => 11,
    ];
}
