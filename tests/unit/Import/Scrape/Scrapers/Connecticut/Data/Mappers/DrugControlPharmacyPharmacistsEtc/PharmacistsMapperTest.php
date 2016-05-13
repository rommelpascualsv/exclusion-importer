<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\PharmacistsMapper;

class PharmacistsMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new PharmacistsMapper();
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
            'FIRST NAME' => 'KENNETH',
            'LAST NAME' => 'PALMOSKI',
            'ADDRESS' => '3052 ARSDALE RD',
            'CITY' => 'WAXHAW',
            'STATE' => 'NC',
            'ZIP' => '28173-7151',
            'LICENSE' => 'PCT.0004487',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '02/01/2016',
            'EXPIRATION DATE' => '01/31/2018',
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => 'KENNETH',
            'last_name' => 'PALMOSKI',
            'business_name' => null,
            'address1' => '3052 ARSDALE RD',
            'address2' => null,
            'city' => 'WAXHAW',
            'county' => null,
            'state' => 'NC',
            'zip' => '28173-7151',
            'license_no' => 'PCT.0004487',
            'license_effective_date' => '2016-02-01',
            'license_expiration_date' => '2018-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}