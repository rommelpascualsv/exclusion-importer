<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PhysicalTherapistMapper;
use Codeception\TestCase\Test;
use UnitTester;


class PhysicalTherapistMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new PhysicalTherapistMapper();
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
            'LICENSE NO.' => '000280',
            'FIRST NAME' => 'CARMELA',
            'LAST NAME' => 'ZANES',
            'ADDRESS1' => '256 PECK LANE  #19',
            'ADDRESS2' => '',
            'CITY' => 'ORANGE',
            'STATE' => 'CT',
            'ZIP' => 'O6477',
            'COUNTY' => '',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'EFFECTIVE DATE' => '07/01/2016',
            'EXPIRATION DATE' => '06/30/2017',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'CARMELA',
            'last_name' => 'ZANES',
            'business_name' => null,
            'address1' => '256 PECK LANE  #19',
            'address2' => null,
            'city' => 'ORANGE',
            'county' => null,
            'state' => 'CT',
            'zip' => 'O6477',
            'license_no' => '000280',
            'license_effective_date' => '2016-07-01',
            'license_expiration_date' => '2017-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
}