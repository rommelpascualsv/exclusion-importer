<?php namespace App\Import\Lists;

class Montana extends ExclusionList
{
    public $dbPrefix = 'mt1';

    public $uri = 'http://dphhs.mt.gov/MontanaHealthcarePrograms/TerminatedExcludedProviders.aspx';

    public $type = 'html';

    public $retrieveOptions = [
    		'htmlFilterElement' => 'div > table',
    		'rowElement'        => 'tr',
    		'columnElement'     => 'td',
    		'headerRow'         => 0,
    		'offset'            => 0
    ];

    public $fieldNames = [
        'provider_name',
        'provider_type',
        'exclusion_termination_date',
        'exclusion_termination_agency',
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'provider_name',
        'provider_type',
        'exclusion_termination_date'
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'exclusion_termination_date' => 2,
    ];
}
