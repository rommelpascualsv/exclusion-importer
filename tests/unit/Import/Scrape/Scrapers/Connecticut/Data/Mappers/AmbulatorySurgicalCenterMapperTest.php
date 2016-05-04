<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\AmbulatorySurgicalCenterMapper;
use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

class AmbulatorySurgicalCenterMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    	$this->mapper = new AmbulatorySurgicalCenterMapper();
    }

    protected function _after()
    {	
    }

    // tests
    public function testGetCsvData()
    {
    	$data = [
    			'SAINT FRANCIS GI ENDOSCOPY, LLC',
    			'360 BLOOMFIELD AVE STE 204',
    			'WINDSOR',
    			'CT',
    			'06095-2700',
    			'321',
    			'ACTIVE',
    			'10/30/2008',
    			'09/30/2016'
    	];
    	
    	$actual = $this->mapper->getCsvData($data);
    	$expected = [
    			'facility_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    			'address' => '360 BLOOMFIELD AVE STE 204',
    			'city' => 'WINDSOR',
    			'state' => 'CT',
    			'zip' => '06095-2700',
    			'license_no' => '321',
    			'status' => 'ACTIVE',
    			'effective_date' => '10/30/2008',
    			'expiration_date' => '09/30/2016'
    	];
    	
    	$this->assertEquals($expected, $actual);
    }
    
    public function testGetDbData()
    {
    	$data = [
    			'facility_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    			'address' => '360 BLOOMFIELD AVE STE 204',
    			'city' => 'WINDSOR',
    			'state' => 'CT',
    			'zip' => '06095-2700',
    			'license_no' => '321',
    			'status' => 'ACTIVE',
    			'effective_date' => '10/30/2008',
    			'expiration_date' => '09/30/2016'
    	];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    			'type' => CsvImporter::TYPE_FACILITY,
    			'values' => [
    					'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    					'address1' => '360 BLOOMFIELD AVE STE 204',
    					'address2' => '',
    					'city' => 'WINDSOR',
    					'county' => '',
    					'state_code' => 'CT',
    					'zip' => '06095-2700',
    					'complete_address' => ''
    			]
    	];
    	 
    	$this->assertEquals($expectedDbData, $dbData);
    }
}