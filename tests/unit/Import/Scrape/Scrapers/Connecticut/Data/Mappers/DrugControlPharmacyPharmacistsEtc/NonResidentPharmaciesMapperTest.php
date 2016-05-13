<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\NonResidentPharmaciesMapper;

class NonResidentPharmaciesMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new NonResidentPharmaciesMapper();
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
            'NONRESIDENT PHARMACY NAME' => 'RELIANT PHARMACY SERVICES',
            'ADDRESS' => '4825 140TH AVE N STE A',
            'CITY' => 'CLEARWATER',
            'STATE' => 'FL',
            'ZIP' => '33762-3822',
            'LICENSE' => 'PCN.0000114',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '09/01/2015',
            'EXPIRATION DATE' => '08/31/2016',
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'RELIANT PHARMACY SERVICES',
            'address1' => '4825 140TH AVE N STE A',
            'address2' => null,
            'city' => 'CLEARWATER',
            'county' => null,
            'state' => 'FL',
            'zip' => '33762-3822',
            'license_no' => 'PCN.0000114',
            'license_effective_date' => '2015-09-01',
            'license_expiration_date' => '2016-08-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}