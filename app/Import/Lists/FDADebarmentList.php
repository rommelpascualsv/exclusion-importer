<?php namespace App\Import\Lists;

class FDADebarmentList extends ExclusionList {

    public $dbPrefix = 'fdadl';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/fdadl/FDA+Debarment+List+(Drug+Product+Applications).csv';

    public $dateColumns = [
        'effective_date' => 2,
        'from_date' => 4,
    ];

    public $retrieveOptions = [
        'headRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'name',
        'aka',
        'effective_date',
        'term_of_debarment',
        'from_date',
    ];

    public $hashColumns = [
        'name',
        'aka',
        'effective_date',
        'term_of_debarment'
    ];

}
