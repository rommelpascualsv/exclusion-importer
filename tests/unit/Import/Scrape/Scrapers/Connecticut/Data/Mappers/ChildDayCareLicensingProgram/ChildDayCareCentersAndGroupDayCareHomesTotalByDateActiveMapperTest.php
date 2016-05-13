<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ChildDayCareLicensingProgram\ChildDayCareCentersAndGroupDayCareHomesTotalByDateActiveMapper;

class ChildDayCareCentersAndGroupDayCareHomesTotalByDateActiveMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ChildDayCareCentersAndGroupDayCareHomesTotalByDateActiveMapper();
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
            'License #' => 'DCCC.11712',
            'Name' => 'COUNTRY GARDEN DAY CARE AND PRESCHOOL',
            'Address' => '250 COUNTRY CLUB RD',
            'City' => 'WATERBURY',
            'Zip' => '06708-3317',
            'Phone' => '(203) 574-4981',
            'Ages Served' => '1 year-12 years',
            'Total Capacity' => '78',
            'Capacity Under 3' => '16',
            'Expiration Date' => '06/30/2018',
            'Legal Operator (LO)' => 'UNITED METHODIST CHURCH WATERBURY INC',
            'Address (LO)' => '250 COUNTRY CLUB ROAD',
            'City (LO)' => 'WATERBURY',
            'Zip (LO)' => '06708'
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'COUNTRY GARDEN DAY CARE AND PRESCHOOL',
            'address1' => '250 COUNTRY CLUB RD',
            'address2' => null,
            'city' => 'WATERBURY',
            'county' => null,
            'state' => null,
            'zip' => '06708-3317',
            'license_no' => 'DCCC.11712',
            'license_effective_date' => null,
            'license_expiration_date' => '2018-06-30',
            'license_status' => null,
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}