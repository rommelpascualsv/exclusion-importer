<?php namespace App\Import\Lists;

class USDosDebar extends ExclusionList
{
    public $dbPrefix = 'usdosd';

    public $type = 'xlsx';

    public $uri = 'https://www.pmddtc.state.gov/compliance/documents/debar.xlsx';

    public $dateColumns = [
        'notice_date'   => 4,
    ];

    public $retrieveOptions = [
        'headRow' => 0,
        'offset'  => 1
    ];

    public $fieldNames = [
        'full_name',
        'aka_name',
        'date_of_birth',
        'notice',
        'notice_date',
    ];

    public $hashColumns = [
        'name',
        'aka',
        'date_of_birth',
        'notice_date'
    ];

    public $shouldHashListName = true;

    public function preProcess()
    {
        parent::preProcess();
        foreach ($this->data as &$record) {

            if (preg_match('/\(.*\)/', $record[0], $matches)) {

                //put aka names in their own column - they're all wrapped in parenthesis
                array_splice($record, 1, 0, $matches);

                //remove the akas from the name
                $record[0] = preg_replace('/\(.*\)/', '', $record[0]);
            } else {
                //insert a blank aka
                array_splice($record, 1, 0, '');
            }

            //remove parenthesis and a.k.a from the aka name field
            $record[1] = preg_replace_callback('/\(|\)|(a.k.a.)/', function () {
                return '';
            }, $record[1]);

            //remove last two records - as per specs
            array_splice($record, -2, 2);
        }
    }
}
