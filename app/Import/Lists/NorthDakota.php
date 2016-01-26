<?php namespace App\Import\Lists;

class NorthDakota extends ExclusionList
{

    /**
     * @var string
     */
    public $dbPrefix = 'nd1';


    /**
     * @var string
     */
    public $uri = 'http://www.nd.gov/dhs/services/medicalserv/medicaid/docs/pro-exclusion-list.xlsx';


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
        'provider_name',
        'provider_verification',
        'business_name_address',
        'medicaid_provider_id',
        'medicaid_provider_number',
        'npi',
        'provider_type',
        'state',
        'exclusion_date',
        'exclusion_reason',
        'exclusion_reason_2',
    );


    /**
     * @var array
     */
    public $hashColumns = [
        'provider_name',
        'business_name_address',
        'medicaid_provider_id',
        'medicaid_provider_number',
        'npi',
        'exclusion_date'
    ];


    /**
     * @var array
     */
    public $dateColumns = [];


    public function postHook()
    {
        $results = app('db')
            ->table($this->dbPrefix . '_records')
            ->select('id', 'business_name_address')
            ->get();

        foreach ($results as $key => $value)
        {
            preg_match('/^(?!N\/A)[\D]+/', $value->business_name_address, $match);

            if ( ! empty($match))
            {
                app('db')
                    ->table($this->dbPrefix . '_records')
                    ->where('id', $value->id)
                    ->update(['business' => $match[0]]);
            }
        }
    }
}