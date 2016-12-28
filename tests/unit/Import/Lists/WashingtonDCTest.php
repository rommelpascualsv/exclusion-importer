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

    public function testWashingtonDCHashColumnsExistsInFieldNames()
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

        dd($items);

        $expectedItems = [
            [
                'company_name' => null,
                'first_name' => 'Ebeneezer',
                'middle_name' => null,
                'last_name' => 'Adewumni',
                'address' => '9418 Annapolis Rd., Suite 204 Lanham, MD 20706',
                'principals' => null,
                'action_date' => 'August 4, 2016',
                'termination_date' => null
            ],
            [
                'company_name' => 'Matrix Corporation',
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
                'address' => '4047 Minnesota Avenue NE, Washington, DC 20019',
                'principals' => null,
                'action_date' => 'May 11, 2015',
                'termination_date' => null
            ],
            [
                'company_name' => null,
                'first_name' => ' Yusuf',
                'middle_name' => null,
                'last_name' => 'Acar',
                'address' => 'Washington, DC 20008-1005',
                'principals' => null,
                'action_date' => 'May 28, 2009 and June 14, 2010',
                'termination_date' => '2012-05-27'
            ],
            [
                'company_name' => 'Advanced Integrated Technologies Corporation',
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
                'address' => '900 17th Street, NW, Suite 900, Washington, DC 20006',
                'principals' => 'Sushil Bansal, President and Chief Executive Officer',
                'action_date' => 'May 28, 2009 and August 18, 2010',
                'termination_date' => '2012-05-27'
            ]
        ];

        $this->assertEquals($expectedItems, $items);
    }
}
