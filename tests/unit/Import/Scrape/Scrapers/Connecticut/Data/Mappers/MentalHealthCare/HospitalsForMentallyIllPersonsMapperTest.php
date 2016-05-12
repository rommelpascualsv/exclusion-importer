<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\HospitalsForMentallyIllPersonsMapper;
use Codeception\TestCase\Test;
use UnitTester;


class HospitalsForMentallyIllPersonsMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new HospitalsForMentallyIllPersonsMapper();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetCsvData()
    {
        $data = [
            '"NATCHAUG HOSPITAL, INC."',
            '189 STORRS RD',
            'MANSFIELD CENTER',
            'CT',
            '06250',
            'PSY.00H0003',
            'ACTIVE',
            '07/01/2009',
            '06/30/2017',
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
            'FACILITY NAME' => '"NATCHAUG HOSPITAL, INC."',
            'ADDRESS' => '189 STORRS RD',
            'CITY' => 'MANSFIELD CENTER',
            'STATE' => 'CT',
            'ZIP' => '06250',
            'LICENSE NO.' => 'PSY.00H0003',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '07/01/2009',
            'EXPIRATION DATE' => '06/30/2017',
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
        $data = [
            'FACILITY NAME' => '"NATCHAUG HOSPITAL, INC."',
            'ADDRESS' => '189 STORRS RD',
            'CITY' => 'MANSFIELD CENTER',
            'STATE' => 'CT',
            'ZIP' => '06250',
            'LICENSE NO.' => 'PSY.00H0003',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '07/01/2009',
            'EXPIRATION DATE' => '06/30/2017',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => '"NATCHAUG HOSPITAL, INC."',
            'address1' => '189 STORRS RD',
            'address2' => null,
            'city' => 'MANSFIELD CENTER',
            'county' => null,
            'state' => 'CT',
            'zip' => '06250',
            'license_no' => 'PSY.00H0003',
            'license_effective_date' => '2009-07-01',
            'license_expiration_date' => '2017-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
    
    public function testMapperFactoryInstance()
    {
        $mapper = MapperFactory::createByKeys('mental_health_care', 'hospitals_for_mentally_ill_persons');
        
        $this->assertInstanceOf(HospitalsForMentallyIllPersonsMapper::class, $mapper);
    }
}