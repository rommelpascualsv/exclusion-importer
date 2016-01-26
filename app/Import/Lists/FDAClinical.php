<?php namespace App\Import\Lists;

class FDAClinical extends ExclusionList {
    
    public $dbPrefix = 'fdac';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/fda-clinical-investigators/FDA-Clinical+Investigators+-+Disqualification+Proceedings_110920150.csv';

    public $type = 'csv';

    public $dateColumns = [
        "date_of_status" => 5,
        "date_nidpoe_issued" => 6,
        "date_nooh_issued" => 7
    ];

    public $retrieveOptions = [
        'headRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'name',
        'center',
        'city',
        'state',
        'status',
        'date_of_status',
        'date_of_nidpoe_issued',
        'date_of_nooh_issued',
    ];

    public $hashColumns = [
        'name',
        'center',
        'status',
        'date_of_status',
    ];
}
