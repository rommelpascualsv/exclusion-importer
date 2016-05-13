<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\NonLegendDrugPermitsMapper;

class NonLegendDrugPermitsMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new NonLegendDrugPermitsMapper();
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
            'BUSINESS NAME' => 'CINDYS MARKET',
            'ADDRESS' => '874 N MAIN ST',
            'CITY' => 'WATERBURY',
            'STATE' => 'CT',
            'ZIP' => '06704-3514',
            'LICENSE NUMBER' => 'PME.0006852',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '05/10/2016',
            'EXPIRATION DATE' => '12/31/2016'
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'CINDYS MARKET',
            'address1' => '874 N MAIN ST',
            'address2' => null,
            'city' => 'WATERBURY',
            'county' => null,
            'state' => 'CT',
            'zip' => '06704-3514',
            'license_no' => 'PME.0006852',
            'license_effective_date' => '2016-05-10',
            'license_expiration_date' => '2016-12-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}