<?php namespace App\Import\Lists;


class Massachusetts extends ExclusionList
{

    public $dbPrefix = 'ma1';

    public $uri = 'http://www.mass.gov/eohhs/docs/masshealth/provlibrary/suspended-excluded-masshealth-providers.xls';

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'provider_name',
        'provider_type',
        'npi',
        'reason',
        'effective_date'
    ];
}