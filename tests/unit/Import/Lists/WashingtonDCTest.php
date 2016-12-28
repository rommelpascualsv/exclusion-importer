<?php namespace Test\Unit;

use App\Import\Lists\WashingtonDC;
use App\Import\Lists\WashingtonDC\WashingtonDCParser;
use Guzzle\Http\Message\Response;

class WashingtonDCTest extends \Codeception\TestCase\Test
{
    private $washingtonDC;

    private $washingtonDCParser;

    protected function _before()
    {
        $this->washingtonDC = new WashingtonDC();
        $this->washingtonDCParser = new WashingtonDCParser();
    }

    protected function _after()
    {
    }

    public function testHealthMilHashColumnsExistsInFieldNames()
    {
        $fieldNames = $this->washingtonDC->fieldNames;
        $hashColumns = $this->washingtonDC->hashColumns;

        foreach ($hashColumns as $hash) {
            $this->assertTrue(in_array($hash, $fieldNames), sprintf("Hash %s not found in field list.", $hash));
        }
    }

    public function testWashingtonDCParser()
    {
        $response = new Response("200", [], file_get_contents("tests/_data/WashingtonDC.html"));

        $items = $this->washingtonDCParser->getItems($response);

        $expectedItems = [
            [
                'companies' => null,
                'firstName' => 'Ebeneezer',
                'middleName' => null,
                'lastName' => 'Adewumni',
                'addresses' => '9418 Annapolis Rd., Suite 204 Lanham, MD 20706',
                'principals' => null,
                'actionDate' => 'August 4, 2016',
                'terminationDate' => null
            ],
            [
                'companies' => 'Matrix Corporation',
                'firstName' => null,
                'middleName' => null,
                'lastName' => null,
                'addresses' => '4047 Minnesota Avenue NE, Washington, DC 20019',
                'principals' => null,
                'actionDate' => 'May 11, 2015',
                'terminationDate' => null
            ],
            [
                'companies' => 'company_name',
                'firstName' => 'first_name',
                'middleName' => 'middle_name',
                'lastName' => 'last_name',
                'addresses' => 'address',
                'principals' => 'principals',
                'actionDate' => 'action_date',
                'terminationDate' => 'termination_date'
            ],
            [
                'companies' => 'company_name',
                'firstName' => 'first_name',
                'middleName' => 'middle_name',
                'lastName' => 'last_name',
                'addresses' => 'address',
                'principals' => 'principals',
                'actionDate' => 'action_date',
                'terminationDate' => 'termination_date'
            ]
        ];

        $this->assertEquals($expectedItems, $items);
    }
}
