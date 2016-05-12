<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\WholesalersOfDrugsCosmeticsAndMedicalDevicesMapper;

class WholesalersOfDrugsCosmeticsAndMedicalDevicesMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new WholesalersOfDrugsCosmeticsAndMedicalDevicesMapper();
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
            'WHOLESALER NAME' => 'GENCO 1 INC',
    	    'ADDRESS' => '1600 RUFFIN MILL RD',
    	    'CITY' => 'SOUTH CHESTERFIELD',
    	    'STATE' => 'VA',
    	    'ZIP' => '23834-5931',
    	    'REGISTRATION' => 'CSW.0003448',
    	    'STATUS' => 'ACTIVE',
    	    'EFFECTIVE DATE' => '07/01/2016',
    	    'EXPIRATION DATE' => '06/30/2017',
        ];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    	    'first_name' => null,
            'last_name' => null,
            'business_name' => 'GENCO 1 INC',
            'address1' => '1600 RUFFIN MILL RD',
            'address2' => null,
            'city' => 'SOUTH CHESTERFIELD',
            'county' => null,
            'state' => 'VA',
            'zip' => '23834-5931',
            'license_no' => 'CSW.0003448',
            'license_effective_date' => '2016-07-01',
            'license_expiration_date' => '2017-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
    	];
    	 
    	$this->assertSame($expectedDbData, $dbData);
    }
}