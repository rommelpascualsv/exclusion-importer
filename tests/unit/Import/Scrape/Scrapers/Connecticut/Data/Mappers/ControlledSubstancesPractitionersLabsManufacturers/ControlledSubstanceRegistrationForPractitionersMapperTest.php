<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ControlledSubstanceRegistrationForPractitionersMapper;

class ControlledSubstanceRegistrationForPractitionersMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ControlledSubstanceRegistrationForPractitionersMapper();
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
            'FIRST NAME' => 'ANGELO',
            'LAST NAME' => 'ACCOMANDO',
            'CITY' => 'EAST HAVEN',
            'STATE' => 'CT',
            'ZIP' => '06512',
            'LICENSE' => 'CSP.0026255',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '03/01/2015',
            'EXPIRATION DATE' => '02/28/2017',
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => 'ANGELO',
            'last_name' => 'ACCOMANDO',
            'business_name' => null,
            'address1' => null,
            'address2' => null,
            'city' => 'EAST HAVEN',
            'county' => null,
            'state' => 'CT',
            'zip' => '06512',
            'license_no' => 'CSP.0026255',
            'license_effective_date' => '2015-03-01',
            'license_expiration_date' => '2017-02-28',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
    
        $this->assertSame($expectedDbData, $dbData);
    }
}