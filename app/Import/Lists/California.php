<?php namespace App\Import\Lists;

class California extends ExclusionList
{
    public $dbPrefix = 'ca1';

    public $uri = 'https://files.medi-cal.ca.gov/pubsdoco/Publications/masters-MTP/zOnlineOnly/susp100-49_z03/suspall_092015.xls';

    public $type = 'xls';

    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_name',
        'aka_dba',
        'addresses',
        'provider_type',
        'license_numbers',
        'provider_numbers',
        'date_of_suspension',
        'active_period'
    ];
}
