<?php namespace App\Import\Lists;


class Connecticut extends ExclusionList
{

    public $dbPrefix = 'ct1';

    public $uri = 'http://www.ct.gov/dss/cwp/view.asp?a=2349&q=310706';

    public $type = 'html';

    public $retrieveOptions = [
        'htmlFilterElement' => 'div > center > table',
        'rowElement'        => 'tr',
        'columnElement'     => 'td',
        'headerRow'         => 1,
        'offset'            => 0
    ];


    public $fieldNames = [
        'name',
        'business',
        'specialty',
        'address',
        'effective_date',
        'period',
        'action'
    ];


    public $hashColumns = [
        'name',
        'business',
        'effective_date',
        'period',
    ];

    public $dateColumns = [
        'effective_date' => 4
    ];


    public $shouldHashListName = true;


    public function preProcess()
    {
        parent::preProcess();
        array_walk_recursive($data, function (&$value) {

            if ($value == "N/A") {
                $value = '';
            }

        });

        $this->data;
    }

}
