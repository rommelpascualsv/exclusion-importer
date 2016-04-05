<?php namespace App\Import\Lists;

class Tennessee extends ExclusionList
{
    public $dbPrefix = 'tn1';

    public $pdfToText = "pdftotext -layout ";

    public $uri = 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf';

    public $type = 'pdf';

    public $fieldNames = [
        'last_name',
        'first_name',
        'business_name',
        'npi',
        'begin_date',
        'reason',
        'end_date'
    ];

    public $retrieveOptions = [
        'headerRow' => 1,
        'offset'    => 0
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'business_name',
        'npi',
        'begin_date',
        'end_date'
    ];

    public $dateColumns = [
        'begin_date' => 4,
        'end_date'   => 6
    ];

    public $shouldHashListName = true;

    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    protected function parse()
    {
        $properSpacing = preg_replace_callback('/1\d{9}/', function ($item) {
            return $item[0] . '  ';
        }, $this->data);

        $rowDelimiter = '^^^^^';
        $columnDelimiter = '~~~~~';

        $rowSplit = preg_replace('/\n/', $rowDelimiter, $properSpacing);
        $columnSplit = preg_replace('/\s{2,}/', $columnDelimiter, $rowSplit);

        $rows = explode($rowDelimiter, $columnSplit);

        foreach ($rows as $row) {

            $rowArray = explode($columnDelimiter, $row);

            if (count($rowArray) == 5 OR count($rowArray) == 6) {
                array_splice($rowArray, 2, 0, '');
            }

            if (count($rowArray) == 6) {
                array_push($rowArray, '');
            }

            if (count($rowArray) == 4) {
                array_splice($rowArray, 1, 0, ['', $rowArray[0]]);
                $rowArray[0] = '';
                array_push($rowArray, '');
            }

            $columns[] = $rowArray;
        }

        array_pop($columns);

        $this->data = $columns;
    }
}
