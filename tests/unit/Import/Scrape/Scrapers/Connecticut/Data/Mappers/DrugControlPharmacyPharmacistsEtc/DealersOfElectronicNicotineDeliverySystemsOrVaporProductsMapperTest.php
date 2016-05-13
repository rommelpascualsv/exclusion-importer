<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\DealersOfElectronicNicotineDeliverySystemsOrVaporProductsMapper;

class DealersOfElectronicNicotineDeliverySystemsOrVaporProductsMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new DealersOfElectronicNicotineDeliverySystemsOrVaporProductsMapper();
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
            'FIRST NAME' => 'DAVID',
            'LAST NAME' => 'GRAVES',
            'BUSINESS NAME' => '',
            'DBA' => 'ALL THE VAPORS LLC',
            'ADDRESS' => '16A CENTER ST',
            'CITY' => 'WALLINGFORD',
            'STATE' => 'CT',
            'ZIP' => '06492-4110',
            'REGISTRATION NUMBER' => 'ECD.00632',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '03/01/2016',
            'EXPIRATION DATE' => '02/28/2017',
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => 'DAVID',
            'last_name' => 'GRAVES',
            'business_name' => 'ALL THE VAPORS LLC',
            'address1' => '16A CENTER ST',
            'address2' => null,
            'city' => 'WALLINGFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06492-4110',
            'license_no' => 'ECD.00632',
            'license_effective_date' => '2016-03-01',
            'license_expiration_date' => '2017-02-28',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}