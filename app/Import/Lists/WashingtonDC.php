<?php namespace App\Import\Lists;

class WashingtonDC extends ExclusionList
{

    /**
     * @var string
     */
    public $dbPrefix = 'dc1';


    /**
     * @var string
     */
    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/washington-dc/dc1.xlsx';


    /**
     * @var string
     */
    public $type = 'xlsx';


    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    /**
     * @var array
     */
    public $fieldNames = [
        'company_name',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'principals',
        'action_date',
        'termination_date'
    ];


    /**
     * @var array
     */
    public $hashColumns = [
        'company_name',
        'first_name',
        'middle_name',
        'last_name',
        'termination_date'
    ];


    /**
     * @var array
     */
    public $dateColumns = [
        'termination_date' => 7
    ];


}
