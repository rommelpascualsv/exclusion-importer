<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\DrugControlPharmacyPharmacistsEtc\PharmacyTechniciansMapper;

class PharmacyTechniciansMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new PharmacyTechniciansMapper();
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
            'FIRST NAME' => 'JAMES',
            'LAST NAME' => 'GREEN',
            'ADDRESS' => '102 JOANNE LN',
            'CITY' => 'HENDERSONVILLE',
            'STATE' => 'NC',
            'ZIP' => '28792-9240',
            'REGISTRATION' => 'PTN.0019175',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '05/10/2016',
            'EXPIRATION DATE' => '03/31/2017',
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => 'JAMES',
            'last_name' => 'GREEN',
            'business_name' => null,
            'address1' => '102 JOANNE LN',
            'address2' => null,
            'city' => 'HENDERSONVILLE',
            'county' => null,
            'state' => 'NC',
            'zip' => '28792-9240',
            'license_no' => 'PTN.0019175',
            'license_effective_date' => '2016-05-10',
            'license_expiration_date' => '2017-03-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}