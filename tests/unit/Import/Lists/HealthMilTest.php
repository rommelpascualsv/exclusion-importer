<?php

use App\Import\Lists\HealthMil;
use App\Import\Lists\HealthMil\HealthMilParser;
use Guzzle\Http\Message\Response;

class HealthMilTest extends \Codeception\TestCase\Test
{
    private $healthMil;
    
    private $healthMilParser;
    
    protected function _before()
    {
        $this->healthMil = new HealthMil();
        $this->healthMilParser= new HealthMilParser();
    }
    
    protected function _after()
    {
    }
    
    public function testHealthMilHashColumnsExistsInFieldNames()
    {
        $fieldNames = $this->healthMil->fieldNames;
        $hashColumns = $this->healthMil->hashColumns;
        
        foreach ($hashColumns as $hash) {
            $this->assertTrue(in_array($hash, $fieldNames), sprintf("Hash %s not found in field list.", $hash));
        }
    }
    
    public function testHealthMilParser()
    {
        $response = new Response("200", [], file_get_contents("tests/_data/HealthMil.html"));
        
        $items = $this->healthMilParser->getItems($response);
        
        $expectedItems = [
            [
                'dateExcluded'  =>  '2016-04-29',
                'term'          =>  '10 years',
                'exclusionDate' =>  '2026-04-29',
                'companies'     =>  'DoCare',
                'firstName'     =>  'Gibson',
                'middleName'    =>  'C',
                'lastName'      =>  'Osuji',
                'title'         =>  ' MD',
                'addresses'     =>  '3317 Gandy Blvd, Tampa, Florida 33611',
                'summary'       =>  'billing for services not rendered, overutilization,
    falsifying medical records'
            ],
            [
                'dateExcluded'  =>  '2016-04-29',
                'term'          =>  '187 months',
                'exclusionDate' =>  '2031-11-29',
                'companies'     =>  'Rabon Communication Enhancement, PLLC',
                'firstName'     =>  'Rebecca',
                'middleName'    =>  'Lee',
                'lastName'      =>  'Rabon',
                'title'         =>  ' SLP',
                'addresses'     =>  '1650 Highway 6, Suite 120, Sugar Land, Texas 77478-4921',
                'summary'       =>  'Billing for services not rendered and falsifying medical
    records'
            ]
        ];
        
        $this->assertEquals($expectedItems, $items);
    }
}