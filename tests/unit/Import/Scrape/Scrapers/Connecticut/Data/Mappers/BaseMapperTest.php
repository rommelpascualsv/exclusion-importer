<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperInterface;

class BaseMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var MapperInterface
     */
    protected $mapper;
    
    protected function _before()
    {
        $csvHeaders = [
            'FACILITY NAME',
            'ADDRESS',
            'CITY',
            'STATE',
            'ZIP',
            'LICENSE NO.',
            'STATUS',
            'EFFECTIVE DATE',
            'EXPIRATION DATE',
            ''
        ];
        $this->mapper = $this->getMockForAbstractClass(BaseMapper::class, [$csvHeaders]);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetCsvData()
    {   
        $data = [
            'SAINT FRANCIS GI ENDOSCOPY, LLC',
            '360 BLOOMFIELD AVE STE 204',
            'WINDSOR',
            'CT',
            '06095-2700',
            '000321',
            'ACTIVE',
            '10/30/2008',
            '09/30/2016',
            ''
        ];
        
        $csvData = $this->mapper->getCsvData($data);
        
        $expectedCsvData = [
            'FACILITY NAME' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'ADDRESS' => '360 BLOOMFIELD AVE STE 204',
            'CITY' => 'WINDSOR',
            'STATE' => 'CT',
            'ZIP' => '06095-2700',
            'LICENSE NO.' => '000321',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '10/30/2008',
            'EXPIRATION DATE' => '09/30/2016',
            '' => ''
        ];
        
        $this->assertSame($expectedCsvData, $csvData);
    }
}