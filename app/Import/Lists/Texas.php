<?php

namespace App\Import\Lists;

class Texas extends ExclusionList
{
    public $dbPrefix = 'tx1';

    public $uri = '/vagrant/storage/app/tx1.xls';

    public $type = 'xls';

    public $shouldHashListName = true;

    public $fieldNames = [
        'company_name',
        'last_name',
        'first_name',
        'mid_initial',
        'occupation',
        'license_number',
        'npi',
        'start_date',
        'add_date',
        'reinstated_date',
        'web_comments'
    ];

    public $hashColumns = [
        'company_name',
        'last_name',
        'first_name',
        'mid_initial',
        'npi',
        'start_date',
        'reinstated_date'
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $dateColumns = [
        'start_date' => 7,
        'add_date' => 8,
        'reinstated_date' => 9,
    ];
}
