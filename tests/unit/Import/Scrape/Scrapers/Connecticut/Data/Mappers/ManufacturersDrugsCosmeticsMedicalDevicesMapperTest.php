<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ManufacturersDrugsCosmeticsMedicalDevicesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperInterface;
use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

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

    // tests
    public function testGetCsvData()
    {
    	$data = [
    			'JOLEN CREME BLEACH CORPORATION',
    			'25 WALLS DR',
    			'FAIRFIELD',
    			'CT',
    			'06824-5156',
    			'CSM.0000025',
    			'ACTIVE',
    			'07/01/2015',
    			'06/30/2016'
    	];
    	 
    	$csvData = $this->mapper->getCsvData($data);
    	
    	$expectedCsvData = [
    			'manufacturer_name' => 'JOLEN CREME BLEACH CORPORATION',
    			'address' => '25 WALLS DR',
    			'city' => 'FAIRFIELD',
    			'state' => 'CT',
    			'zip' => '06824-5156',
    			'registration' => 'CSM.0000025',
    			'status' => 'ACTIVE',
    			'effective_date' => '07/01/2015',
    			'expiration_date' => '06/30/2016'
    	];
    	 
    	$this->assertEquals($expectedCsvData, $csvData);
    }
    
    public function testGetDbData()
    {
    	$csvData = [
    			'manufacturer_name' => 'JOLEN CREME BLEACH CORPORATION',
    			'address' => '25 WALLS DR',
    			'city' => 'FAIRFIELD',
    			'state' => 'CT',
    			'zip' => '06824-5156',
    			'registration' => 'CSM.0000025',
    			'status' => 'ACTIVE',
    			'effective_date' => '07/01/2015',
    			'expiration_date' => '06/30/2016'
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