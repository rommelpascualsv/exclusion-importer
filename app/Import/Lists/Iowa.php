<?php namespace App\Import\Lists;


class Iowa extends ExclusionList
{

    public $dbPrefix = 'ia1';


    public $uri = "https://dhs.iowa.gov/sites/default/files/2016-01-31.PI_.term-suspend-probation.zip";


    public $type = 'zip';


    public $fieldNames = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction',
        'sanction_end_date'
    ];


    public $hashColumns = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction_end_date'
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $dateColumns = [
        'sanction_start_date' => 0,
        'sanction_end_date' => 6
    ];

    public function preProcess($data)
    {
        foreach ($data as &$record){

            $npi = explode(' ', $record[1]);

            foreach ($npi as $key => $value){
                //TODO: only keep valid NPI's
            }

            $npis[] = $npi;
        }


    dd($npis);
    }

}
