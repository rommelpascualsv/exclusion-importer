<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\FamilyDayCareHomesTotalByDateActiveMapper;

class FamilyDayCareHomesTotalByDateActiveMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new FamilyDayCareHomesTotalByDateActiveMapper();
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
            'License #' => 'DCFH.54543',
            'Last Name' => 'MACRI',
            'First Name' => 'JANET',
            'Address' => '176 BRENTWOOD DR',
            'City' => 'BRISTOL',
            'Zip' => '06010-2507',
            'Phone' => '(860) 261-7914',
            'Regular Capacity' => '6',
            'School Age Capacity' => '3',
            'Expiration Date' => '08/31/2017'
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => 'JANET',
            'last_name' => 'MACRI',
            'business_name' => null,
            'address1' => '176 BRENTWOOD DR',
            'address2' => null,
            'city' => 'BRISTOL',
            'county' => null,
            'state' => null,
            'zip' => '06010-2507',
            'license_no' => 'DCFH.54543',
            'license_effective_date' => null,
            'license_expiration_date' => '2017-08-31',
            'license_status' => null,
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}