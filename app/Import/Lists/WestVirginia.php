<?php namespace App\Import\Lists;

class WestVirginia extends ExclusionList
{

    /**
     * @var string
     */
    public $dbPrefix = 'wv2';


    /**
     * @var string
     */
    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/west-virginia/wv2.xlsx';


    /**
     * @var string
     */
    public $type = 'xlsx';


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
        'npi_number',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'generation',
        'credentials',
        'provider_type',
        'city',
        'state',
        'exclusion_date',
        'reason_for_exclusion',
        'reinstatement_date',
        'reinstatement_reason'
    );


    /**
     * @var array
     */
    public $hashColumns = [
        'npi_number',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'exclusion_date',
        'reinstatement_date',
    ];


    /**
     * @var array
     */
    public $dateColumns = [
        'exclusion_date' => 10,
    ];
}
