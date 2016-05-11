<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

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

    protected $npiColumnName = "npi_number";

    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * Parse the input data
     */
    private function parse()
    {
        $data = [];

        // iterate each row
        foreach ($this->data as $row) {
            $npiColumnIndex = $this->getNpiColumnIndex();

            // set npi number array
            $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);

            $data[] = $row;
        }

        // set back to global data
        $this->data = $data;
    }

    public function postHook()
    {
        app('db')->table('fl2_records')
            ->whereNotIn(app('db')->raw('TRIM(`sanction_type`)'), ['', 'SUSPENSION', 'TERMINATION', 'NONE'])
            ->delete();
    }
}
