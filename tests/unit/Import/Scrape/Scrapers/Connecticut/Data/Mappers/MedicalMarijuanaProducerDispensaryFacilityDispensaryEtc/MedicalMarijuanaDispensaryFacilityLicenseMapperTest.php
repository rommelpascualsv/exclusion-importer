<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc\MedicalMarijuanaDispensaryFacilityLicenseMapper;
use Codeception\TestCase\Test;
use UnitTester;

class MedicalMarijuanaDispensaryFacilityLicenseMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new MedicalMarijuanaDispensaryFacilityLicenseMapper();
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
            'NAME' => '"ARROW ALTERNATIVE CARE, INC"',
            'ADDRESS' => '92 WESTON ST',
            'CITY' => 'HARTFORD',
            'STATE' => 'CT',
            'ZIP' => '06120-1510',
            'LICENSE NUMBER' => 'MMDF.0000001',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '04/10/2016',
            'EXPIRATION DATE' => '04/09/2017',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => '"ARROW ALTERNATIVE CARE, INC"',
            'address1' => '92 WESTON ST',
            'address2' => null,
            'city' => 'HARTFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06120-1510',
            'license_no' => 'MMDF.0000001',
            'license_effective_date' => '2016-04-10',
            'license_expiration_date' => '2017-04-09',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
