<?php namespace App\Import\Lists;


class Kansas extends ExclusionList
{

    /**
     * @var string
     */
    public $dbPrefix = 'ks1';


    /**
     * @var string
     */
    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/kansas/ks.csv';


    /**
     * @var string
     */
    public $type = 'csv';


    /**
     * @var array
     */
    public $retrieveOptions = array(
        'headerRow' => 0,
        'offset' => 1
    );


    /**
     * @var array
     */
    public $fieldNames = array(
        'termination_date',
        'name',
        'd_b_a',
        'provider_type',
        'kmap_provider_number',
        'npi',
        'comments'
    );


    /**
     * @var array
     */
    public $hashColumns = [
        'termination_date',
        'name',
        'd_b_a',
        'npi',
    ];


    /**
     * @var array
     */
    public $dateColumns = [
        'termination_date' => 0
    ];
}