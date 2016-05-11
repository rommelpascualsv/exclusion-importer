<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\AssistedLivingServiceAgencyMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Codeception\TestCase\Test;
use UnitTester;

class AssistedLivingServiceAgencyMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new AssistedLivingServiceAgencyMapper();
    }

    protected function _after()
    {
        
    }

    // tests
    public function testGetCsvData()
    {
        $data = [
            'MASONICARE HOME HEALTH AND HOSPICE',
            '33 N PLAINS INDUSTRIAL RD',
            'WALLINGFORD',
            'CT',
            '06492-5841',
            '000128',
            'ACTIVE',
            '10/30/2008',
            '09/30/2016',
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
            'FACILITY NAME' => 'MASONICARE HOME HEALTH AND HOSPICE',
            'ADDRESS' => '33 N PLAINS INDUSTRIAL RD',
            'CITY' => 'WALLINGFORD',
            'STATE' => 'CT',
            'ZIP' => '06492-5841',
            'LICENSE NO.' => '000128',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '10/30/2008',
            'EXPIRATION DATE' => '09/30/2016'
        ];

        $this->assertSame($expected, $actual);
    }

    public function testGetDbData()
    {
        $data = [
            'FACILITY NAME' => 'MASONICARE HOME HEALTH AND HOSPICE',
            'ADDRESS' => '33 N PLAINS INDUSTRIAL RD',
            'CITY' => 'WALLINGFORD',
            'STATE' => 'CT',
            'ZIP' => '06492-5841',
            'LICENSE NO.' => '000128',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '10/30/2008',
            'EXPIRATION DATE' => '09/30/2016'
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'MASONICARE HOME HEALTH AND HOSPICE',
            'address1' => '33 N PLAINS INDUSTRIAL RD',
            'address2' => null,
            'city' => 'WALLINGFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06492-5841',
            'license_no' => '000128',
            'license_effective_date' => '2008-10-30',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

    public function testMapperFactoryInstance()
    {
        $mapper = MapperFactory::createByKeys('long_term_care_assisted_living_facilities_home_health_care_hospice', 'assisted_living_service_agency');

        $this->assertInstanceOf(AssistedLivingServiceAgencyMapper::class, $mapper);
    }

}
