<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Texas extends ExclusionList
{
    public $dbPrefix = 'tx1';

    public $uri = '/vagrant/storage/app/tx1.xls';

    public $type = 'xls';

    public $shouldHashListName = true;

    public $fieldNames = [
        'company_name',
        'last_name',
        'first_name',
        'mid_initial',
        'occupation',
        'license_number',
        'npi',
        'start_date',
        'add_date',
        'reinstated_date',
        'web_comments'
    ];

    public $hashColumns = [
        'company_name',
        'last_name',
        'first_name',
        'mid_initial',
        'npi',
        'start_date',
        'reinstated_date'
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $dateColumns = [
        'start_date' => 7,
        'add_date' => 8,
        'reinstated_date' => 9,
    ];

    protected $npiColumnName = "npi";

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

            // set npi number array
            $npiColumnIndex = $this->getNpiColumnIndex();
            $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);

            // populate the array data
            $data[] = $row;
        }

        // set back to global data
        $this->data = $data;
    }
}
