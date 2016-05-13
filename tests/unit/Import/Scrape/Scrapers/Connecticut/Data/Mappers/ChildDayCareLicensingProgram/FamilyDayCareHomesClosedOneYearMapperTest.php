<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\FamilyDayCareHomesClosedOneYearMapper;

class FamilyDayCareHomesClosedOneYearMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new FamilyDayCareHomesClosedOneYearMapper();
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
            'Close Date' => '01/04/2016',
    	    'License #' => 'DCFH.56156',
    	    'Last Name' => 'DIAZ',
    	    'First Name' => 'YUDELSA',
    	    'Address' => '298 JACKSON AVE',
    	    'City' => 'BRIDGEPORT',
    	    'Zip' => '06606-5570',
    	    'Phone' => '(203) 870-9844'
        ];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    	    'first_name' => 'YUDELSA',
            'last_name' => 'DIAZ',
            'business_name' => null,
            'address1' => '298 JACKSON AVE',
            'address2' => null,
            'city' => 'BRIDGEPORT',
            'county' => null,
            'state' => null,
            'zip' => '06606-5570',
            'license_no' => 'DCFH.56156',
            'license_effective_date' => null,
            'license_expiration_date' => '2016-01-04',
            'license_status' => null,
            'license_status_reason' => null
    	];
    	 
    	$this->assertSame($expectedDbData, $dbData);
    }
}