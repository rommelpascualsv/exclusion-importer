<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

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
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'provider_name',
        'provider_verification',
        'business_name_address',
        'medicaid_provider_id',
        'medicaid_provider_number',
        'npi',
        'provider_type',
        'state',
        'exclusion_date',
        'exclusion_type',
        'exclusion_reason',
        'exclusion_reason_2',
        'medicaid_provider_number_2',
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'provider_name',
        'business_name_address',
        'medicaid_provider_id',
        'medicaid_provider_number',
        'state',
        'npi',
        'exclusion_date'
    ];

    /**
     * @var array
     */
    public $dateColumns = [];

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

            $newRow = $this->setRowToSaveFormat($row);

            $exclusion = $this->splitExclusionDateField($row[8]);
            $newRow[8] = $exclusion['date'];
            $newRow[9] = $exclusion['type'];

    	    // populate the array data
    	    $data[] = $newRow;
    	}
    		
    	// set back to global data
    	$this->data = $data;
    }
    
    public function postHook()
    {
        $results = app('db')
            ->table($this->dbPrefix . '_records')
            ->select('id', 'business_name_address')
            ->get();

        foreach ($results as $key => $value) {
            preg_match('/^(?!N\/A)[\D]+/', $value->business_name_address, $match);

            if ( ! empty($match)) {
                app('db')
                    ->table($this->dbPrefix . '_records')
                    ->where('id', $value->id)
                    ->update(['business' => $match[0]]);
            }
        }
    }

    /**
     * Splits the string and returns an Array containing keys
     * type and date.
     *
     * Example:
     *      array['type']
     *      array['date']
     *
     * @param $value
     * @return mixed
     */
    private function splitExclusionDateField($value)
    {
        $value = trim($value);

        $splitValue = preg_split('/\s+/', $value);

        return count($splitValue) > 1
            ? ['type' => $splitValue[0], 'date' => $this->formatDate($splitValue[1])]
            : ['type' => null, 'date' => $this->formatDate($splitValue[0])];

    }

    /**
     * Date is formatted to year-month-day e.g. 2016-01-01
     *
     * @param $date
     * @return bool|string
     */
    private function formatDate($date)
    {
        return $formattedDate = date('Y-m-d', strtotime($date));
    }

    /**
     * Copy the provided array to a new array that
     * corresponds to the field of nd1_records which
     * will be use when storing to the table.
     *
     * @param $row
     * @return array
     */
    private function setRowToSaveFormat($row)
    {
        $newRow = array_slice($row, 0, 8);
        $newRow[8] = '';
        $newRow[9] = '';
        $newRow[10] = array_get($row, 9, '');
        $newRow[11] = array_get($row, 10, '');
        $newRow[12] = array_get($row, 11, '');

        return $newRow;
    }

}
