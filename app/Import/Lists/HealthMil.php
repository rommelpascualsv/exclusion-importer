<?php

namespace App\Import\Lists;

use App\Import\Lists\HealthMil\HealthMilParser;

class HealthMil extends ExclusionList
{
    public $dbPrefix = 'healthmil';

    public $uri = 'http://www.health.mil/Military-Health-Topics/Access-Cost-Quality-and-Safety/Quality-And-Safety-of-Healthcare/Program-Integrity/Sanctioned-Providers';

    public $type = 'custom';

    public $fieldNames = [
        'date_excluded',
        'term',
        'exclusion_end_date',
        'companies',
        'first_name',
        'middle_name',
        'last_name',
        'addresses',
        'summary'
    ];


    public $hashColumns = [
        'date_excluded',
        'term',
        'exclusion_date',
        'companies',
        'firstName',
        'middleName',
        'lastName',
    ];

    private $parser;


    public function customRetriever()
    {
        $this->parser = new HealthMilParser();
        $response = $this->parser->crawlFormPage();
        $response = $this->parser->getViewAllList($response);
        $items = $this->parser->getItems($response);

        return $items;
    }
}
