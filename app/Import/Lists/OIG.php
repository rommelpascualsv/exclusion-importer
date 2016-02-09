<?php

namespace App\Import\Lists;

class OIG extends ExclusionList
{
    public $dbPrefix = 'oig';

    public $uri = 'http://go.usa.gov/cn5nj';

    public $type = 'csv';

    public $dateColumns = [
        'excldate'   => 14,
        'reindate'   => 15,
        'waiverdate' => 16,
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'  => 1
    ];

    public $fieldNames = [
        'lastname',
        'firstname',
        'midname',
        'busname',
        'general',
        'specialty',
        'upin',
        'npi',
        'dob',
        'address',
        'city',
        'state',
        'zip',
        'excltype',
        'excldate',
        'reindate',
        'waiverdate',
        'wvrstate'
    ];

    public $hashColumns = [
        'lastname',
        'firstname',
        'midname',
        'busname',
        'upin',
        'npi',
        'dob',
        'excldate',
    ];


    public function preProcess($records)
    {

        foreach ($records as &$record){
            $dob_parts = explode('/', $record[8]);
            $record[8] = ( count($dob_parts) == 3 ) ? '19' . $dob_parts[2] . '-' . $dob_parts[0] . '-' . $dob_parts[1] : null ;

            $record[7] = $record[7] != '0000000000' ?: null;
        }

        return $records;
    }
}
