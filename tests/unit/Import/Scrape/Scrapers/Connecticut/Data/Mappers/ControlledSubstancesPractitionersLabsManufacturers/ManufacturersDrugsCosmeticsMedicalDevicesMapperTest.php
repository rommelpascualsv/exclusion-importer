<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperInterface;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ManufacturersDrugsCosmeticsMedicalDevicesMapper;

class ManufacturersDrugsCosmeticsMedicalDevicesMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	
    /**
     * @var MapperInterface
     */
    protected $mapper;
    
    protected function _before()
    {
    	$this->mapper = new ManufacturersDrugsCosmeticsMedicalDevicesMapper();
    }

    protected function _after()
    {
    }
    
    public function testGetDbData()
    {
    	$csvData = [
    	    'MANUFACTURER NAME' => 'JOLEN CREME BLEACH CORPORATION',
            'ADDRESS' => '25 WALLS DR',
            'CITY' => 'FAIRFIELD',
            'STATE' => 'CT',
            'ZIP' => '06824-5156',
            'REGISTRATION' => 'CSM.0000025',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '07/01/2015',
            'EXPIRATION DATE' => '06/30/2016'
    	];
    
    	$dbData = $this->mapper->getDbData($csvData);
    
    	$expectedDbData = [
            'first_name' => '',
            'last_name' => '',
            'business_name' => 'JOLEN CREME BLEACH CORPORATION',
            'address1' => '25 WALLS DR',
            'address2' => '',
            'city' => 'FAIRFIELD',
            'county' => '',
            'state' => 'CT',
            'zip' => '06824-5156',
            'license_no' => 'CSM.0000025',
            'license_effective_date' => '2015-07-01',
            'license_expiration_date' => '2016-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => ''
        ];
    
    	$this->assertEquals($expectedDbData, $dbData);
    }
}