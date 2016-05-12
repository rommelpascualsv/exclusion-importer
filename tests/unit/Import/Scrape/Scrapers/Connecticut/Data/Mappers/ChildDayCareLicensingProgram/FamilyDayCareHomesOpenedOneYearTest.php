<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\FamilyDayCareHomesOpenedOneYear;

class FamilyDayCareHomesOpenedOneYearTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new FamilyDayCareHomesOpenedOneYear();
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
            'Initial License Date' => '05/04/2016',
    	    'License #' => 'DCFH.56788',
    	    'Last Name' => 'CASTILLO',
    	    'First Name' => 'RAYZA',
    	    'Address'  => '158 GREENWOOD AVE FL 1',
    	    'City' => 'WATERBURY',
    	    'Zip' => '06704-2540',
    	    'Phone' => '(203) 440-8289'
        ];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    	    'first_name' => 'RAYZA',
            'last_name' => 'CASTILLO',
            'business_name' => null,
            'address1' => '158 GREENWOOD AVE FL 1',
            'address2' => null,
            'city' => 'WATERBURY',
            'county' => null,
            'state' => null,
            'zip' => '06704-2540',
            'license_no' => 'DCFH.56788',
            'license_effective_date' => '2016-05-04',
            'license_expiration_date' => null,
            'license_status' => null,
            'license_status_reason' => null
    	];
    	 
    	$this->assertSame($expectedDbData, $dbData);
    }
}