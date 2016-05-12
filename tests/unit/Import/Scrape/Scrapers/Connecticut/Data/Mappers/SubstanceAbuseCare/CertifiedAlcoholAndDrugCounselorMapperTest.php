<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare\CertifiedAlcoholAndDrugCounselorMapper;
use Codeception\TestCase\Test;
use UnitTester;

class CertifiedAlcoholAndDrugCounselorMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new CertifiedAlcoholAndDrugCounselorMapper();
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
            'LICENSE NO.' => '000005',
            'FIRST NAME' => 'RONALD',
            'LAST NAME' => 'BAKER',
            'ADDRESS1' => '779 HOPEVILLE RD',
            'ADDRESS2' => '',
            'CITY' => 'GRISWOLD',
            'STATE' => 'CT',
            'ZIP' => 'O6351-1212',
            'COUNTY' => '',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'ISSUE DATE' => '10/02/1998',
            'EXPIRATION DATE' => '09/30/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'RONALD',
            'last_name' => 'BAKER',
            'business_name' => null,
            'address1' => '779 HOPEVILLE RD',
            'address2' => null,
            'city' => 'GRISWOLD',
            'county' => null,
            'state' => 'CT',
            'zip' => 'O6351-1212',
            'license_no' => '000005',
            'license_effective_date' => '1998-10-02',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
