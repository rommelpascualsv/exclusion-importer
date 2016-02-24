<?php namespace

App\Import\Lists;

class FDAClinical extends ExclusionList
{
    public $dbPrefix = 'fdac';

    public $uri = 'http://www.accessdata.fda.gov/scripts/SDA/sdExportData.cfm?sd=clinicalinvestigatorsdisqualificationproceedings&exportType=msexcel';

    public $type = 'xls';

    public $shouldHashListName = true;

    public $dateColumns = [
        "date_of_status" => 5,
        "date_nidpoe_issued" => 6,
        "date_nooh_issued" => 7
    ];

    public $retrieveOptions = [
        'headRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'name',
        'center',
        'city',
        'state',
        'status',
        'date_of_status',
        'date_of_nidpoe_issued',
        'date_of_nooh_issued',
    ];

    public $hashColumns = [
        'name',
        'status',
        'date_of_status',
    ];


    public function preProcess()
    {
        parent::preProcess();
        $removableSuffixes = [
            ', MD',
            ', DVM',
            ', PhD',
            ', DO',
        ];

        $removableSuffixesAsString = "/" . implode('|', $removableSuffixes) . "/";

        foreach ($this->data as &$record) {
            $record[0] = preg_replace_callback($removableSuffixesAsString, function () {
                return '';
            }, $record[0]);

            $record[4] = preg_replace('/1/', '', $record[4]);

            $record = array_slice($record, 0, 8);
        }

        $this->data;
    }
}
