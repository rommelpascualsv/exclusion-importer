<?php namespace App\Import\Lists;

class PHS extends ExclusionList {

    public $dbPrefix = 'phs';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/phs/PHS_2015-11-09.csv';

    public $type = 'csv';

    public $dateColumns = [
        "debarment_until" => 3,
        "no_phs_advisory_until" => 4,
        "certification_of_work_until" => 5,
        "supervision_until" => 6,
    ];

    public $retrieveOptions = [
        'headRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_name',
        'debarment_until',
        'no_phs_advisory_until',
        'certification_of_work_until',
        'supervision_until',
        'memo',
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'middle_name',
        'debarment_until',
    ];

}
