<?php namespace App\Import\Lists;

class Michigan extends ExclusionList
{
    public $dbPrefix = 'mi1';

    public $uri = 'http://www.michigan.gov/documents/mdch/MI_Sanctioned_Provider_List_375503_7.xls';

    public $type = 'xls';

    public $shouldHashListName = true;

    public $retrieveOptions = [
        'headerRow' => 1,
        'offset' => 2
    ];

    public $fieldNames = [
        'entity_name',
        'last_name',
        'first_name',
        'middle_name',
        'provider_category',
        'npi_number',
        'city',
        'license_number',
        'sanction_date_1',
        'sanction_source_1',
        'sanction_date_2',
        'sanction_source_2',
        'reason'
    ];

    public $hashColumns = [
        'entity_name',
        'last_name',
        'first_name',
        'middle_name',
        'npi_number',
        'license_number',
        'sanction_date_1',
        'sanction_date_2'
    ];

    public $dateColumns = [
        'sanction_date_1' => 8,
        'sanction_date_2' => 10
    ];
}
