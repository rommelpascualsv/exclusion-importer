<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PodiatristMapper;
use Codeception\TestCase\Test;
use UnitTester;


class PodiatristMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new PodiatristMapper();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetCsvData()
    {
        $data = [
            '000014',
            'JAMES',
            'BLUME',
            '508 BLAKE ST',
            '',
            'NEW HAVEN',
            'CT',
            'O6515-1287',
            'New Haven',
            'ACTIVE',
            'CURRENT',
            '10/03/1955',
            '03/31/2017'
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
            'LICENSE NO.' => '000014',
            'FIRST NAME' => 'JAMES',
            'LAST NAME' => 'BLUME',
            'ADDRESS1' => '508 BLAKE ST',
            'ADDRESS2' => '',
            'CITY' => 'NEW HAVEN',
            'STATE' => 'CT',
            'ZIP' => 'O6515-1287',
            'COUNTY' => 'New Haven',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'ISSUE DATE' => '10/03/1955',
            'EXPIRATION DATE' => '03/31/2017'
        ];

        $this->assertSame($expected, $actual);
    }

    public function testGetDbData()
    {
        $data = [
            'LICENSE NO.' => '000014',
            'FIRST NAME' => 'JAMES',
            'LAST NAME' => 'BLUME',
            'ADDRESS1' => '508 BLAKE ST',
            'ADDRESS2' => '',
            'CITY' => 'NEW HAVEN',
            'STATE' => 'CT',
            'ZIP' => 'O6515-1287',
            'COUNTY' => 'New Haven',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'ISSUE DATE' => '10/03/1955',
            'EXPIRATION DATE' => '03/31/2017'
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'JAMES',
            'last_name' => 'BLUME',
            'business_name' => null,
            'address1' => '508 BLAKE ST',
            'address2' => null,
            'city' => 'NEW HAVEN',
            'county' => 'New Haven',
            'state' => 'CT',
            'zip' => 'O6515-1287',
            'license_no' => '000014',
            'license_effective_date' => '1955-10-03',
            'license_expiration_date' => '2017-03-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
    
    public function testMapperFactoryInstance()
    {
        $mapper = \App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory::createByKeys('healthcare_practitioners', 'podiatrist');
        
        $this->assertInstanceOf(PodiatristMapper::class, $mapper);
    }
}