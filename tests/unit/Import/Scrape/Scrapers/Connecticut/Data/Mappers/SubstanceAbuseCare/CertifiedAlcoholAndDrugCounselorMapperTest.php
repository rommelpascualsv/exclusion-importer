<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\SubstanceAbuseCare;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
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

    // tests
    public function testGetCsvData()
    {
        $data = [
            '000005',
            'RONALD',
            'BAKER',
            '779 HOPEVILLE RD',
            '',
            'GRISWOLD',
            'CT',
            'O6351-1212',
            '',
            'ACTIVE',
            'CURRENT',
            '10/02/1998',
            '09/30/2016',
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
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

        $this->assertSame($expected, $actual);
    }

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
    
    public function testMapperFactoryInstance()
    {
        $mapper = MapperFactory::createByKeys('substance_abuse_care', 'certified_alcohol_and_drug_counselor');
        
        $this->assertInstanceOf(CertifiedAlcoholAndDrugCounselorMapper::class, $mapper);
    }
}