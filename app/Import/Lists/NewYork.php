<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class NewYork extends ExclusionList
{
    public $dbPrefix = 'nyomig';

    public $uri = 'http://www.omig.ny.gov/data/gensplistns.php';

    public $type = 'txt';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'business',
        'provider_number',
        'npi',
        'provtype',
        'action_date',
        'action_type',
        'provider_number_2'
    ];

    public $hashColumns = [
        'business',
        'provider_number',
        'npi',
        'action_date'
    ];

    public $shouldHashListName = true;

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
            $npiColumnIndex = $this->getNpiColumnIndex();

            // set provider number
            $row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $npiColumnIndex));

            // set npi number array
            $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);

            // populate the array data
            $data[] = $row;
        }

        // set back to global data
        $this->data = $data;
    }
}
