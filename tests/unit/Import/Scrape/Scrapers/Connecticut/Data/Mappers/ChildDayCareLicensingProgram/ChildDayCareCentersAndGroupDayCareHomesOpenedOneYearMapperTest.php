<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\ChildDayCareCentersAndGroupDayCareHomesOpenedOneYearMapper;

class ChildDayCareCentersAndGroupDayCareHomesOpenedOneYearMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ChildDayCareCentersAndGroupDayCareHomesOpenedOneYearMapper();
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
            'Type' => 'Child Care Center',
            'Initial License Date' => '01/11/2016',
            'License #' => 'DCCC.70281',
            'Name' => "THE CHILDREN'S CORNER",
            'Address' => '115 BARLOW MOUNTAIN RD',
            'City' => 'RIDGEFIELD',
            'Zip' => '06877-1902',
            'Phone' => '(203) 438-3737',
            'Legal Operator (LO)' => 'CADENCE EDUCATION INC.',
            'Address (LO)' => '8767 W. VIA DE VENTURA SUITE 200',
            'City (LO)' => 'SCOTTSDALE',
            'Zip (LO)' => '85258'
        ];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    	    'first_name' => null,
            'last_name' => null,
            'business_name' => "THE CHILDREN'S CORNER",
            'address1' => '115 BARLOW MOUNTAIN RD',
            'address2' => null,
            'city' => 'RIDGEFIELD',
            'county' => null,
            'state' => null,
            'zip' => '06877-1902',
            'license_no' => 'DCCC.70281',
            'license_effective_date' => '2016-01-11',
            'license_expiration_date' => null,
            'license_status' => null,
            'license_status_reason' => null
    	];
    	 
    	$this->assertSame($expectedDbData, $dbData);
    }
}