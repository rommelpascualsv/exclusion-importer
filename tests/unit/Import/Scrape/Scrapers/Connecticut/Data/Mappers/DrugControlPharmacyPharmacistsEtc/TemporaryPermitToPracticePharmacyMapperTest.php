<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\TemporaryPermitToPracticePharmacyMapper;

class TemporaryPermitToPracticePharmacyMapperTest extends \Codeception\TestCase\Test
{

    /**
     *
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new TemporaryPermitToPracticePharmacyMapper();
    }

    protected function _after()
    {}
    
    // tests
    
    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
        $data = [
            'FIRST NAME' => 'IQBAL',
            'LAST NAME' => 'MASSODI',
            'CITY' => 'FARMINGTON',
            'STATE' => 'CT',
            'ZIP' => '06032-1266',
            'COUNTY' => 'Hartford',
            'LICENSE' => 'PTP.0000441',
            'STATUS' => 'ACTIVE',
            'REASON' => 'NONE',
            'EFFECTIVE DATE' => '02/10/2016',
            'EXPIRATION DATE' => '06/30/2016',
        ];
        
        $dbData = $this->mapper->getDbData($data);
        
        $expectedDbData = [
            'first_name' => 'IQBAL',
            'last_name' => 'MASSODI',
            'business_name' => null,
            'address1' => null,
            'address2' => null,
            'city' => 'FARMINGTON',
            'county' => 'Hartford',
            'state' => 'CT',
            'zip' => '06032-1266',
            'license_no' => 'PTP.0000441',
            'license_effective_date' => '2016-02-10',
            'license_expiration_date' => '2016-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'NONE'
        ];
        
        $this->assertSame($expectedDbData, $dbData);
    }
}