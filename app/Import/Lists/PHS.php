<?php

namespace App\Import\Lists;

class PHS extends ExclusionList
{
    public $dbPrefix = 'phs';

    public $uri = 'http://ori.hhs.gov/ORI_PHS_alert.html';

    public $type = 'html';

    public $shouldHashListName = true;

    public $dateColumns = [
        "debarment_until" => 3,
        "no_phs_advisory_until" => 4,
        "certification_of_work_until" => 5,
        "supervision_until" => 6,
    ];

    public $retrieveOptions = [
        'htmlFilterElement' => 'table',
        'rowElement'        => 'tr',
        'columnElement'     => 'td',
        'offset'            => 0,
        'headerRow'         => 0
    ];

    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_name',
        'debarment_until',
        'no_phs_advisory_until',
        'certification_of_work_until',
        'supervision_until',
        'memo',
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'middle_name',
        'debarment_until',
    ];

    public function preProcess()
    {
        parent::preProcess();
        foreach ($this->data as &$record) {
            array_splice($record, 7, 2);
        }
    }

}
