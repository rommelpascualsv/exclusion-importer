<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityBackerLicenseMapper;
use Codeception\TestCase\Test;
use UnitTester;

class MedicalMarijuanaDispensaryFacilityBackerLicenseMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new MedicalMarijuanaDispensaryFacilityBackerLicenseMapper();
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
            'FIRST NAME' => 'MEREDITH',
            'LAST NAME' => 'ELMER',
            'CITY' => 'EAST LYME',
            'STATE' => 'CT',
            'ZIP' => '06333',
            'LICENSE' => 'MMDB.0000010',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '09/26/2015',
            'EXPIRATION DATE' => '09/25/2016',
            'Supervision' => 'THAMES VALLEY ALTERNATIVE RELIEF',
            'Supervisor Credential #' => 'MMDF.0000005',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'MEREDITH',
            'last_name' => 'ELMER',
            'business_name' => 'THAMES VALLEY ALTERNATIVE RELIEF',
            'address1' => null,
            'address2' => null,
            'city' => 'EAST LYME',
            'county' => null,
            'state' => 'CT',
            'zip' => '06333',
            'license_no' => 'MMDB.0000010',
            'license_effective_date' => '2015-09-26',
            'license_expiration_date' => '2016-09-25',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
