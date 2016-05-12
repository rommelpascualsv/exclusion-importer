<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\LongTermCareAssistedLivingFacilitiesHomeHealthCareHospice\NursingHomeAdministratorMapper;
use Codeception\TestCase\Test;
use UnitTester;

class NursingHomeAdministratorMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new NursingHomeAdministratorMapper();
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
            'LICENSE NO.' => '000050',
            'FIRST NAME' => 'PAULINE',
            'LAST NAME' => 'GAMER',
            'ADDRESS1' => '150 WESTPORT RD',
            'ADDRESS2' => '',
            'CITY' => 'WILTON',
            'STATE' => 'CT',
            'ZIP' => 'O6897',
            'COUNTY' => 'Fairfield',
            'STATUS' => 'ACTIVE',
            'REASON' => 'RENEWAL APPLICATION SENT',
            'ISSUE DATE' => '01/01/1901',
            'EXPIRATION DATE' => '05/31/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'PAULINE',
            'last_name' => 'GAMER',
            'business_name' => null,
            'address1' => '150 WESTPORT RD',
            'address2' => null,
            'city' => 'WILTON',
            'county' => 'Fairfield',
            'state' => 'CT',
            'zip' => 'O6897',
            'license_no' => '000050',
            'license_effective_date' => '1901-01-01',
            'license_expiration_date' => '2016-05-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'RENEWAL APPLICATION SENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
