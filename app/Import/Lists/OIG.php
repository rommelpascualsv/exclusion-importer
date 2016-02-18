<?php

namespace App\Import\Lists;


class OIG extends ExclusionList
{
    public $dbPrefix = 'oig';


    public $uri = 'http://oig.hhs.gov/exclusions/downloadables/UPDATED.csv';


    public $type = 'csv';


    public $dateColumns = [
        'excldate'   => 14,
        'reindate'   => 15,
        'waiverdate' => 16,
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 1
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


    public function postProcess($data)
    {
        return array_map(function ($record) {
            if ($record['npi'] == 0) {
                $record['npi'] = null;
            }

            if ($record['dob'] == '0000-00-00' OR $record['dob'] == '') {
                $record['dob'] = null;
            }

            return $record;
        }, $data);
    }
}