<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals\ChildrensHospitalsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Codeception\TestCase\Test;
use UnitTester;


class ChildrensHospitalsMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ChildrensHospitalsMapper();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetCsvData()
    {
        $data = [
            'CONNECTICUT CHILDREN\'S MEDICAL CENTER',
            '282 WASHINGTON ST',
            'HARTFORD',
            'CT',
            '06106-3322',
            '0002CH',
            'ACTIVE',
            '01/01/2010',
            '12/31/2017'
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
            'FACILITY NAME' => 'CONNECTICUT CHILDREN\'S MEDICAL CENTER',
            'ADDRESS' => '282 WASHINGTON ST',
            'CITY' => 'HARTFORD',
            'STATE' => 'CT',
            'ZIP' => '06106-3322',
            'LICENSE NO.' => '0002CH',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '01/01/2010',
            'EXPIRATION DATE' => '12/31/2017',
        ];

        $this->assertSame($expected, $actual);
    }

    public function testGetDbData()
    {
        $data = [
            'FACILITY NAME' => 'CONNECTICUT CHILDREN\'S MEDICAL CENTER',
            'ADDRESS' => '282 WASHINGTON ST',
            'CITY' => 'HARTFORD',
            'STATE' => 'CT',
            'ZIP' => '06106-3322',
            'LICENSE NO.' => '0002CH',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '01/01/2010',
            'EXPIRATION DATE' => '12/31/2017',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'CONNECTICUT CHILDREN\'S MEDICAL CENTER',
            'address1' => '282 WASHINGTON ST',
            'address2' => null,
            'city' => 'HARTFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06106-3322',
            'license_no' => '0002CH',
            'license_effective_date' => '2010-01-01',
            'license_expiration_date' => '2017-12-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
    
    public function testMapperFactoryInstance()
    {
        $mapper = MapperFactory::createByKeys('hospitals', 'childrens_hospitals');
        
        $this->assertInstanceOf(ChildrensHospitalsMapper::class, $mapper);
    }
}