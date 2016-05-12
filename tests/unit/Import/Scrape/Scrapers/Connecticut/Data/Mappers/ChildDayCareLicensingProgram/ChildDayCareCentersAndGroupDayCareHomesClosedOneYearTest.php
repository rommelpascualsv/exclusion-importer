<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\ChildDayCareCentersAndGroupDayCareHomesClosedOneYear;

class ChildDayCareCentersAndGroupDayCareHomesClosedOneYearTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ChildDayCareCentersAndGroupDayCareHomesClosedOneYear();
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
            'Type' => 'Child Care Center',
    	    'Close Date' => '01/05/2016',
    	    'License #' => 'DCCC.13121',
    	    'Name' => 'POOH CORNER PRESCHOOL LEARNING CENTER',
    	    'Address' => '55 OTROBANDO AVENUE',
    	    'City' => 'NORWICH',
    	    'Zip' => '06360',
    	    'Phone' => '(860) 889-3528',
    	    'Legal Operator (LO)' => 'GENESIS HOLDING GROUP LLC',
    	    'Address (LO)' => '55 OTROBANDO AVENUE',
    	    'City (LO)' => 'NORWICH',
    	    'Zip (LO)' => '06360'
        ];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    	    'first_name' => null,
            'last_name' => null,
            'business_name' => 'POOH CORNER PRESCHOOL LEARNING CENTER',
            'address1' => '55 OTROBANDO AVENUE',
            'address2' => null,
            'city' => 'NORWICH',
            'county' => null,
            'state' => null,
            'zip' => '06360',
            'license_no' => 'DCCC.13121',
            'license_effective_date' => null,
            'license_expiration_date' => '2016-01-05',
            'license_status' => null,
            'license_status_reason' => null
    	];
    	 
    	$this->assertSame($expectedDbData, $dbData);
    }
}