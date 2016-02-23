<?php namespace App\Import\Lists;

use App\Common\Entity\SocialSecurityNumber;

class CustomSpectrumDebar extends ExclusionList
{
    public $dbPrefix = 'cus_spectrum_debar';

    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/custom-debar-list/Cume0630.xlsx';

    public $fieldNames = [
        'name',
        'title',
        'ssn',
        'tin',
        'npi',
        'date_of_birth',
        'street_address',
        'city',
        'state',
        'zip',
        'debar_code',
        'suspend_date',
        'debar_date'
    ];

    public $dateColumns = [
        'date_of_birth' => 5,
        'suspend_date' => 11,
        'debar_date' => 12
    ];

    public $hashColumns = [
        'name',
        'ssn_last_four',
        'tin',
        'npi',
        'date_of_birth',
        'debar_code',
        'suspend_date',
        'debar_date'
    ];

    public $shouldHashListName = true;

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public function postProcess()
    {
        $this->data = array_map(function($record) {
            if ($record['ssn'] == null) {
                $record['ssn_last_four'] = null;
                return $record;
            }

            try {
                $ssn = new SocialSecurityNumber($record['ssn']);
                $record['ssn'] = (string) $ssn;
                $record['ssn_last_four'] = $ssn->lastFour();
            } catch (\Exception $e) {
                $record['ssn_last_four'] = null;
            }
            return $record;
        }, $this->data);
    }

}
