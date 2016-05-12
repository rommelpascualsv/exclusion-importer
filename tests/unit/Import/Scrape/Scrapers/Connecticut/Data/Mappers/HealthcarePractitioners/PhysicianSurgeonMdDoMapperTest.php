<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\PhysicianSurgeonMdDoMapper;

class PhysicianSurgeonMdDoMapperTest extends \Codeception\TestCase\Test
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new PhysicianSurgeonMdDoMapper();
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
            'LICENSE NO.' => '',
            'FIRST NAME' => 'NAFTALI',
            'LAST NAME' => 'KAMINSKI',
            'ADDRESS1' => '245 MCKINLEY AVE',
            'ADDRESS2' => '',
            'CITY' => 'NEW HAVEN',
            'STATE' => 'CT',
            'ZIP' => 'O6515-2OO9',
            'COUNTY' => 'New Haven',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'ISSUE DATE' => '04/21/2015',
            'EXPIRATION DATE' => '',
            'DEGREE TYPE' => '',
            'Specialty' => '',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'NAFTALI',
            'last_name' => 'KAMINSKI',
            'business_name' => null,
            'address1' => '245 MCKINLEY AVE',
            'address2' => null,
            'city' => 'NEW HAVEN',
            'county' => 'New Haven',
            'state' => 'CT',
            'zip' => 'O6515-2OO9',
            'license_no' => null,
            'license_effective_date' => '2015-04-21',
            'license_expiration_date' => null,
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
