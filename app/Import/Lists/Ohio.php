<?php namespace App\Import\Lists;


//TODO: unset two columns

class Ohio extends ExclusionList
{

    public $dbPrefix = 'oh1';


    public $uri = 'http://medicaid.ohio.gov/Portals/0/Providers/Enrollment%20and%20Support/ExclusionSuspensionList.xlsx';


    public $type = 'xlsx';


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $fieldNames = [
        'last_name',
        'first_name',
        'organization_name',
        'date_of_birth',
        'npi',
        'address1',
        'address2',
        'city',
        'state',
        'zip_code',
        'provider_id',
        'status',
        'action_date',
        'date_added',
        'provider_type',
        'date_revised'
    ];

    public $hashColumns = [

        'last_name',
        'first_name',
        'organization_name',
        'date_of_birth',
        'npi',
        'provider_id',
        'status',
        'action_date',
    ];

    public $dateColumns = [

        'date_of_birth' => 3,
        'action_date' => 12,
        'date_added' => 13,
        'date_revised' => 15,
    ];

    public function postHook()
    {
        app('db')->statement('UPDATE oh1_records SET npi = NULL WHERE npi = 0');
    }
}