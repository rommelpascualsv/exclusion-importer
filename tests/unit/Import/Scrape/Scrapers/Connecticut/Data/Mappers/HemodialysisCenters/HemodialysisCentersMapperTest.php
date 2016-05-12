<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\HemodialysisCenters;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HemodialysisCenters\HemodialysisCentersMapper;
use Codeception\TestCase\Test;
use UnitTester;

class HemodialysisCentersMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new HemodialysisCentersMapper();
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
            'FACILITY NAME' => 'BLACK ROCK DIALYSIS',
            'ADDRESS' => '427 STILLSON RD',
            'CITY' => 'FAIRFIELD',
            'STATE' => 'CT',
            'ZIP' => '06824-3158',
            'LICENSE NO.' => 'HEMO.0000283',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '10/06/2008',
            'EXPIRATION DATE' => '09/30/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'BLACK ROCK DIALYSIS',
            'address1' => '427 STILLSON RD',
            'address2' => null,
            'city' => 'FAIRFIELD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06824-3158',
            'license_no' => 'HEMO.0000283',
            'license_effective_date' => '2008-10-06',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}