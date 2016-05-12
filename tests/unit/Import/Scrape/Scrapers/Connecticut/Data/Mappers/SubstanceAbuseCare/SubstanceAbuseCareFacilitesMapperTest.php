<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare\SubstanceAbuseCareFacilitesMapper;
use Codeception\TestCase\Test;
use UnitTester;

class SubstanceAbuseCareFacilitesMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new SubstanceAbuseCareFacilitesMapper();
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
            'FACILITY NAME' => '"CONNECTICUT RENAISSANCE, INC."',
            'ADDRESS' => '4 BYINGTON PL',
            'CITY' => 'NORWALK',
            'STATE' => 'CT',
            'ZIP' => '06850-3309',
            'LICENSE NO.' => '000327',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '10/01/2009',
            'EXPIRATION DATE' => '09/30/2017',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => '"CONNECTICUT RENAISSANCE, INC."',
            'address1' => '4 BYINGTON PL',
            'address2' => null,
            'city' => 'NORWALK',
            'county' => null,
            'state' => 'CT',
            'zip' => '06850-3309',
            'license_no' => '000327',
            'license_effective_date' => '2009-10-01',
            'license_expiration_date' => '2017-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
