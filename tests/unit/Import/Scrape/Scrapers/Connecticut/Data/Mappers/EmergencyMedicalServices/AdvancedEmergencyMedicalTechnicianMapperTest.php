<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices\AdvancedEmergencyMedicalTechnicianMapper;
use Codeception\TestCase\Test;
use UnitTester;

class AdvancedEmergencyMedicalTechnicianMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new AdvancedEmergencyMedicalTechnicianMapper();
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
            'CERTIFICATE NO.' => '000002',
            'FIRST NAME' => 'BRYAN',
            'LAST NAME' => 'RYAN',
            'ADDRESS1' => '43 HOLBROOK PL',
            'ADDRESS2' => '',
            'CITY' => 'ANSONIA',
            'STATE' => 'CT',
            'ZIP' => 'O64O1-12O7',
            'COUNTY' => 'New Haven',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'ISSUE DATE' => '08/07/1999',
            'EXPIRATION DATE' => '04/01/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'BRYAN',
            'last_name' => 'RYAN',
            'business_name' => null,
            'address1' => '43 HOLBROOK PL',
            'address2' => null,
            'city' => 'ANSONIA',
            'county' => 'New Haven',
            'state' => 'CT',
            'zip' => 'O64O1-12O7',
            'license_no' => '000002',
            'license_effective_date' => '1999-08-07',
            'license_expiration_date' => '2016-04-01',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
