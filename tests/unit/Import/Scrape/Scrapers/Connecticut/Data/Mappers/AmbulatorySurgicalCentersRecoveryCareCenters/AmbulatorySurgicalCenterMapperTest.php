<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\AmbulatorySurgicalCentersRecoveryCareCenters;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\AmbulatorySurgicalCentersRecoveryCareCenters\AmbulatorySurgicalCenterMapper;

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
    
    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
    	$data = [
            'FACILITY NAME' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'ADDRESS' => '360 BLOOMFIELD AVE STE 204',
            'CITY' => 'WINDSOR',
            'STATE' => 'CT',
            'ZIP' => '06095-2700',
            'LICENSE NO.' => '000321',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '10/30/2008',
            'EXPIRATION DATE' => '09/30/2016',
        ];
    	 
    	$dbData = $this->mapper->getDbData($data);
    	 
    	$expectedDbData = [
    	    'first_name' => null,
            'last_name' => null,
            'business_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'address1' => '360 BLOOMFIELD AVE STE 204',
            'address2' => null,
            'city' => 'WINDSOR',
            'county' => null,
            'state' => 'CT',
            'zip' => '06095-2700',
            'license_no' => '000321',
            'license_effective_date' => '2008-10-30',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
    	];
    	 
    	$this->assertSame($expectedDbData, $dbData);
    }
    
    /**
     * To test that mapper's getDbData result for empty dates will result
     * to a null value.
     */
    public function testGetDbDataLicenseDatesEmpty()
    {
        $data = [
            'FACILITY NAME' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'ADDRESS' => '360 BLOOMFIELD AVE STE 204',
            'CITY' => 'WINDSOR',
            'STATE' => 'CT',
            'ZIP' => '06095-2700',
            'LICENSE NO.' => '000321',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '',
            'EXPIRATION DATE' => '',
        ];
        
        $dbData = $this->mapper->getDbData($data);
        
        $expectedDbData = [
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'address1' => '360 BLOOMFIELD AVE STE 204',
            'address2' => null,
            'city' => 'WINDSOR',
            'county' => null,
            'state' => 'CT',
            'zip' => '06095-2700',
            'license_no' => '000321',
            'license_effective_date' => null,
            'license_expiration_date' => null,
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
        
        $this->assertSame($expectedDbData, $dbData);
    }
}