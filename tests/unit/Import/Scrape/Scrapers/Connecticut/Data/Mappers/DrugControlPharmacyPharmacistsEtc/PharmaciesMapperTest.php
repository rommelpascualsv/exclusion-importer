<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\PharmaciesMapper;

class PharmaciesMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new PharmaciesMapper();
    }

    protected function _after()
    {
    }

    // tests
    
    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
        $data = [
            'PHARMACY NAME' => 'WHALLEY DRUG',
            'ADDRESS' => '399 WHALLEY AVE',
            'CITY' => 'NEW HAVEN',
            'STATE' => 'CT',
            'ZIP' => '06511-3008',
            'LICENSE NUMBER' => 'PCY.0002327',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '04/07/2016',
            'EXPIRATION DATE' => '08/31/2016',
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'WHALLEY DRUG',
            'address1' => '399 WHALLEY AVE',
            'address2' => null,
            'city' => 'NEW HAVEN',
            'county' => null,
            'state' => 'CT',
            'zip' => '06511-3008',
            'license_no' => 'PCY.0002327',
            'license_effective_date' => '2016-04-07',
            'license_expiration_date' => '2016-08-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}