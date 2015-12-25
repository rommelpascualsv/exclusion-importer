<?php namespace App\Import\Lists;


//TODO: unset two "sanction source" columns before insert

class Michigan extends ExclusionList
{

    public $dbPrefix = 'mi1';

    public $uri = 'http://www.michigan.gov/documents/mdch/MI_Sanctioned_Provider_List_375503_7.xls';

    public $retrieveOptions = array(
        'headerRow' => 1,
        'offset' => 2
    );

    public $fieldNames = array(
        'entity_name',
        'last_name',
        'first_name',
        'middle_name',
        'provider_category',
        'npi_number',
        'city',
        'license_number',
        'sanction_date_1',
        'sanction_date_2',
        'reason'
    );
}