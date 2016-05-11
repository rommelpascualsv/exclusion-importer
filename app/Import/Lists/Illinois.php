<?php namespace App\Import\Lists;

class Illinois extends ExclusionList
{
    public $dbPrefix = 'il1';
    
    public $uri = 'http://www.illinois.gov/hfs/oig/Documents/ILMedicaidSanctionsExcel20160421.xls';
    
    public $type = 'xls';    
    
    public $hashColumns = [
        'il1_id',
        'ProvName',
        'LIC',
        'ACTION_DT',
        'ACTION_TYPE',
    ];
    
    public $dateColumns = [
        'ACTION_DT' => 4
    ];
    
    public $fieldNames = [
        'il1_id',
        'ProvName',
        'LIC',
        'AFFILIATION',
        'ACTION_DT',
        'ACTION_TYPE',
        'ADDRESS',
        'ADDRESS2',
        'CITY',
        'STATE',
        'ZIP_CODE',
        'NEW_ADDITION'
    ];
    
    public $shouldHashListName = true;
}
