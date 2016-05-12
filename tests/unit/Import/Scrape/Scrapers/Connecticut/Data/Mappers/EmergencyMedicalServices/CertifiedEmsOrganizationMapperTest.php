<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices\CertifiedEmsOrganizationMapper;
use Codeception\TestCase\Test;
use UnitTester;


class CertifiedEmsOrganizationMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new CertifiedEmsOrganizationMapper();
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
            'SERVICE NAME' => '"West Hartford Fire Department, (Town of)"',
            'ADDRESS' => '50 S MAIN ST',
            'CITY' => 'WEST HARTFORD',
            'STATE' => 'CT',
            'ZIP' => '06107-2485',
            'LICENSE NO.' => 'C155P1',
            'STATUS' => 'ACTIVE',
            'EXPIRATION DATE' => '12/31/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => '"West Hartford Fire Department, (Town of)"',
            'address1' => '50 S MAIN ST',
            'address2' => null,
            'city' => 'WEST HARTFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06107-2485',
            'license_no' => 'C155P1',
            'license_effective_date' => null,
            'license_expiration_date' => '2016-12-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
}