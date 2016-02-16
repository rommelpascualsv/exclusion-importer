<?php

namespace App\Import\Lists;

class FDADebarmentList extends ExclusionList
{
    public $dbPrefix = 'fdadl';

    public $uri = 'http://www.fda.gov/ICECI/EnforcementActions/FDADebarmentList/ucm2005408.htm';

    public $type = 'html';

    public $shouldHashListName = true;

    public $dateColumns = [
        'effective_date' => 1,//index number before adding in aka column
        'from_date'      => 3,//index number before adding in aka column
    ];

    public $retrieveOptions = [
        'htmlFilterElement' => 'article > div:nth-child(6) > table',
        'rowElement'        => 'tr',
        'columnElement'     => 'td',
        'offset'            => 0,
        'headerRow'         => 0
    ];

    public $fieldNames = [
        'name',
        'aka',
        'effective_date',
        'term_of_debarment',
        'from_date',
    ];

    public $hashColumns = [
        'name',
        'aka',
        'effective_date',
        'term_of_debarment'
    ];

    public function preProcess($data)
    {
        $replacableStrings = [
            '^'                            => ' Mandatory Debarment',
            '%'                            => ' Permissive Debarment',
            '*'                            => ' Hearing requested and denied',
            '#'                            => ' Acquiesced to Debarment',
            '+'                            => ' Special Termination of Debarment',
            '++'                           => ' Order to Withdraw Order of Debarment',
            '!!!'                          => ' Rescission of Debarment Order',
            'aka'                          => '~',
            'a.k.a.'                       => '~',
            'NMI'                          => '',
            'One person removed from list' => '',
            '**'                           => '',
            '***'                          => '',
            '****'                         => '',

        ];

        foreach ($data as $key => &$record) {
            $stringOfRecord = implode('~', $record);

            $newStringOfRecord = str_replace(
                array_keys($replacableStrings),
                array_values($replacableStrings),
                $stringOfRecord
            );

            $record = explode('~', $newStringOfRecord);

            if (trim($record[0], chr(0xC2).chr(0xA0)) == '') {
                unset($data[$key]);
                continue;
            }

            if (count($record) == 5) {
                array_splice($record, 1, 0, '');
            }

            array_pop($record);
        }

        return $data;
    }
}
