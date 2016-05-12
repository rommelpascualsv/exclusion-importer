<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\EmergencyMedicalServices\ParamedicMapper;
use Codeception\TestCase\Test;
use UnitTester;


class ParamedicMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ParamedicMapper();
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
            'LICENSE NO.' => '000001',
            'FIRST NAME' => 'WILLIAM',
            'LAST NAME' => 'ACKLEY',
            'ADDRESS1' => '684 LONG RIDGE RD',
            'ADDRESS2' => '',
            'CITY' => 'STAMFORD',
            'STATE' => 'CT',
            'ZIP' => 'O69O2-1225',
            'COUNTY' => '',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'ISSUE DATE' => '10/01/1997',
            'EXPIRATION DATE' => '09/30/2016'
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'WILLIAM',
            'last_name' => 'ACKLEY',
            'business_name' => null,
            'address1' => '684 LONG RIDGE RD',
            'address2' => null,
            'city' => 'STAMFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => 'O69O2-1225',
            'license_no' => '000001',
            'license_effective_date' => '1997-10-01',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
}