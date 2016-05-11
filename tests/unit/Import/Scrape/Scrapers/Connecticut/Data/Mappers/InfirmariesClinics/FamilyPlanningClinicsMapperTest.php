<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics\FamilyPlanningClinicsMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Codeception\TestCase\Test;
use UnitTester;


class FamilyPlanningClinicsMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new FamilyPlanningClinicsMapper();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetCsvData()
    {
        $data = [
            '"PLANNED PARENTHOOD OF SOUTHERN NEW ENGLAND, INC."',
            '100 Grand Street',
            'New Britain',
            'CT',
            '06051',
            'FP.0000038',
            'ACTIVE',
            '01/01/2010',
            '12/31/2016',
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
            'FACILITY NAME' => '"PLANNED PARENTHOOD OF SOUTHERN NEW ENGLAND, INC."',
            'ADDRESS' => '100 Grand Street',
            'CITY' => 'New Britain',
            'STATE' => 'CT',
            'ZIP' => '06051',
            'LICENSE NO.' => 'FP.0000038',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '01/01/2010',
            'EXPIRATION DATE' => '12/31/2016'
        ];

        $this->assertSame($expected, $actual);
    }

    public function testGetDbData()
    {
        $data = [
            'FACILITY NAME' => '"PLANNED PARENTHOOD OF SOUTHERN NEW ENGLAND, INC."',
            'ADDRESS' => '100 Grand Street',
            'CITY' => 'New Britain',
            'STATE' => 'CT',
            'ZIP' => '06051',
            'LICENSE NO.' => 'FP.0000038',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '01/01/2010',
            'EXPIRATION DATE' => '12/31/2016'
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => '"PLANNED PARENTHOOD OF SOUTHERN NEW ENGLAND, INC."',
            'address1' => '100 Grand Street',
            'address2' => null,
            'city' => 'New Britain',
            'county' => null,
            'state' => 'CT',
            'zip' => '06051',
            'license_no' => 'FP.0000038',
            'license_effective_date' => '2010-01-01',
            'license_expiration_date' => '2016-12-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
    
    public function testMapperFactoryInstance()
    {
        $mapper = MapperFactory::createByKeys('infirmaries_clinics', 'family_planning_clinics');
        
        $this->assertInstanceOf(FamilyPlanningClinicsMapper::class, $mapper);
    }
}