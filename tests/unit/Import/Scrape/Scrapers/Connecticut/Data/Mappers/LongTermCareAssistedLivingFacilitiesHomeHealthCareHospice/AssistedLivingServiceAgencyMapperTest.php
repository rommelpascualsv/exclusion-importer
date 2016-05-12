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

    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
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

}
