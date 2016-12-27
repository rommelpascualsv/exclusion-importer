<?php namespace App\Import\Lists;

use App\Import\Lists\WashingtonDC\WashingtonDCParser;

class WashingtonDC extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'dc1';

    /**
     * @var string
     */
    public $uri = 'http://ocp.dc.gov/page/excluded-parties-list';

    /**
     * @var string
     */
    public $type = 'custom';

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'action_date',
        'termination_date',
        'company_name',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'principals'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'company_name',
        'first_name',
        'middle_name',
        'last_name',
        'termination_date'
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'termination_date' => 'termination_date'
    ];

    private $parser;

    public function retrieveData()
    {
        $this->parser = new WashingtonDCParser();
        $response = $this->parser->crawlFormPage();
        $items = $this->parser->getItems($response);
        $this->data = $items;
    }
}
