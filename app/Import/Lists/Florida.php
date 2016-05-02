<?php namespace App\Import\Lists;

class Florida extends ExclusionList
{
    public $dbPrefix = 'fl2';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/florida/FOReport.xls';

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'provider',
        'medicaid_provider_number',
        'license_number',
        'npi_number',
        'provider_type',
        'date_rendered',
        'sanction_type',
        'violation_code',
        'fine_amount',
        'sanction_date',
        'ahca_case_number',
        'formal_informal_case_number',
        'document_type'
    ];

    public $hashColumns = [
        'provider',
        'medicaid_provider_number',
        'license_number',
        'npi_number',
        'date_rendered',
        'sanction_date'
    ];

    public $dateColumns = [
        'date_rendered' => 5,
        'sanction_date' => 9
    ];

    public $shouldHashListName = true;

    public $npiColumn = 3;
    
    public function postHook()
    {
        app('db')->table('fl2_records')
            ->whereNotIn(app('db')->raw('TRIM(`sanction_type`)'),['' , 'SUSPENSION', 'TERMINATION', 'NONE'])
            ->delete();
    }
}
