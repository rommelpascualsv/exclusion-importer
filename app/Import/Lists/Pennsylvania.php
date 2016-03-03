<?php namespace App\Import\Lists;


//TODO: unset a bunch of columns

class Pennsylvania extends ExclusionList
{

    public $dbPrefix = 'pa1';

    public $isExcel = false;

    public $uri = 'http://services.dpw.state.pa.us/dhs/medicheck.txt';

    public $type = 'txt';


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $fieldNames = [
        'ProviderName',
        'LicenseNumber',
        'Status',
        'BeginDate',
        'EndDate',
        'CAO',
        'ListDate',
        //'IND_DELTD',
        //'TXT_REASON_DELTD',
        //'DTE_DELTD',
        'IND_CHGD',
        'DTE_CHANGE_LAST',
        'NAM_LAST_PROVR',
        'NAM_FIRST_PROVR',
        'NAM_MIDDLE_PROVR',
        'NAM_TITLE_PROVR',
        'NAM_SUFFIX_PROVR',
        'NAM_PROVR_ALT',
        'NAM_BUSNS_MP',
        'IDN_NPI',
        //'TXT_CMT'
    ];

    public $hashColumns = [
        'ProviderName',
        'LicenseNumber',
        'Status',
        'BeginDate',
        'EndDate',
        'ListDate',
        'NAM_LAST_PROVR',
        'NAM_FIRST_PROVR',
        'NAM_MIDDLE_PROVR',
        'NAM_BUSNS_MP',
        'IDN_NPI',
    ];


    public $dateColumns = [
        'BeginDate' => 3,
        'EndDate' => 4,
        'ListDate' => 6,
    ];
}