<?php namespace App\Import\Lists;

class SouthCarolina extends ExclusionList
{

    public $dbPrefix = 'sc1';


    public $uri = 'https://www.scdhhs.gov/sites/default/files/Exclusion%20Provider%20List%20for%20DHHS%20Website_14.xls';


    public $retrieveOptions = [
        'headerRow' => 2,
        'offset' => 4
    ];

    public $hashColumns = [
        'entity',
        'npi',
        'city',
        'state',
        'zip',
        'date_excluded'
    ];

    public $fieldNames = [
        'entity',
        'npi',
        'city',
        'state',
        'zip',
        'provider_type',
        'date_excluded'
    ];

    public $dateColumns = [
        'date_excluded' => 6
    ];

    public $shouldHashListName = true;


    public function postProcess($data)
    {
        array_walk_recursive($data, function (&$value, $key) {
            if ($key === 'npi' && ! is_numeric($value)) {
                $value = null;
            }
        });

        return $data;
    }
}
