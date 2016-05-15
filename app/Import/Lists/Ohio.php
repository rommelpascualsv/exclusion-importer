<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

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
        'provider_id_2',
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
        'provider_id_2',
        'status',
        'action_date'
    ];

    public $dateColumns = [

        'date_of_birth' => 3,
        'action_date' => 13,
        'date_added' => 14,
        'date_revised' => 16,
    ];
    
    public $shouldHashListName = true;
    
    protected $npiColumnName = "npi";
    protected $providerNumberColumnName = "provider_id_2";

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

            $providerNumberIndex = $this->getProviderNumberColumnIndex();
            $npiColumnIndex = $this->getNpiColumnIndex();

            $row = array_merge(array_slice($row, 0, $providerNumberIndex), [''], array_slice($row, $providerNumberIndex));

    	    // set provider number
    	    $row = PNHelper::setProviderNumberValue(
                $row,
                PNHelper::getProviderNumberValue($row, $npiColumnIndex),
                $providerNumberIndex
            );

    	    // set npi number array
    	    $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
    	    	
    	    // populate the array data
    	    $data[] = $row;
    	}

        // set back to global data
    	$this->data = $data;
    }
}
