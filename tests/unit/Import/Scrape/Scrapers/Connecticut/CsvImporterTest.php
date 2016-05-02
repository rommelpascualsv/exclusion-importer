<?php
namespace Import\Scrape\Scrapers\Connecticut;


use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

class CsvImporterTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	
    /**
     * @var CsvImporter
     */
    protected $importer;
    
    protected function _before()
	{
    	$this->db = app('db')->connection();
    	$this->importer = new CsvImporter($this->db);
    	
    	/* insert categories */
    	$this->tester->haveRecord('ct_roster_categories', [
    			'id' => 1,
    			'key' => 'ambulatory_surgical_centers_recovery_care_centers',
    			'name' => 'Ambulatory Surgical Centers/Recovery Care Centers',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    	
    	/* insert options */
    	$this->tester->haveRecord('ct_roster_options', [
    			'id' => 1,
    			'category_id' => 1,
    			'key' => 'ambulatory_surgical_center',
    			'name' => 'Ambulatory Surgical Center',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    }

    protected function _after()
    {
    }
    
    public function testDbInsertFacility()
    {
    	$name = 'SAINT FRANCIS GI ENDOSCOPY, LLC';
    	$timestamp = '2016-05-01 01:30:00';
    	
    	$this->importer->dbInsertFacility($name, $timestamp);
    	
    	$this->tester->seeRecord('ct_roster_facilities', [
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00' 
    	]);
    }
    
    public function testDbFindFacilityIdByName()
    {
    	$this->tester->haveRecord('ct_roster_facilities', [
    			'id' => 3,
    			'name' => 'Some Roster'
    	]);
    	
    	$name = 'Some Roster';
    	 
    	$facilityId = $this->importer->dbFindFacilityIdByName($name);
    	 
    	$this->assertEquals(3, $facilityId);
    }
    
    public function testDbFindFacilityIdByNameNotFound()
    {    	
    	$name = 'Some Unknown Roster';
    	 
    	$facilityId = $this->importer->dbFindFacilityIdByName($name);
    	 
    	$this->assertNull($facilityId);
    }
    
    public function testDbInsertRoster()
    {
    	$data = [
	    	'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
	    	'address1' => '360 BLOOMFIELD AVE STE 204',
	    	'address2' => '',
	    	'city' => 'WINDSOR',
	    	'county' => '',
	    	'state_code' => 'CT',
	    	'zip' => '06095-2700',
	    	'complete_address' => ''
    	];
    	$optionId = 1;
    	$timestamp = '2016-05-01 01:30:00';
    	
    	$this->importer->dbInsertRoster($data, $optionId, $timestamp);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => 1,
    			'address1' => '360 BLOOMFIELD AVE STE 204',
		    	'address2' => '',
		    	'city' => 'WINDSOR',
		    	'county' => '',
		    	'state_code' => 'CT',
		    	'zip' => '06095-2700',
		    	'complete_address' => ''
    	]);
    }
    
    public function testDbInsertRosterWithExistingFacility()
    {
    	// insert existing facility
    	$this->tester->haveRecord('ct_roster_facilities', [
    			'id' => 23,
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    	]);
    	
    	$data = [
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    			'address1' => '360 BLOOMFIELD AVE STE 204',
    			'address2' => '',
    			'city' => 'WINDSOR',
    			'county' => '',
    			'state_code' => 'CT',
    			'zip' => '06095-2700',
    			'complete_address' => ''
    	];
    	$optionId = 1;
    	$timestamp = '2016-05-01 01:30:00';
    	 
    	$this->importer->dbInsertRoster($data, $optionId, $timestamp);
    	 
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => 1,
    			'facility_id' => 23,
    			'practitioner_id' => null,
    			'address1' => '360 BLOOMFIELD AVE STE 204',
    			'address2' => '',
    			'city' => 'WINDSOR',
    			'county' => '',
    			'state_code' => 'CT',
    			'zip' => '06095-2700',
    			'complete_address' => ''
    	]);
    }
    
    public function testImportCsv()
    {
    	$data = [
    			'category' => 'ambulatory_surgical_centers_recovery_care_centers',
    			'option' => 'ambulatory_surgical_center',
    			'file_path' => codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
    	];
    	$timestamp = '2016-05-01 01:30:00';
    	
    	$this->importer->importCsv($data, $timestamp);
    	
    	/* check first and last row in the csv if they are in the database */	
    	$firstFacility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC'
    	]);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'facility_id' => $firstFacility->id,
    			'address1' => '360 BLOOMFIELD AVE STE 204',
    			'city' => 'WINDSOR',
    			'zip' => '06095-2700'
    	]);
    	
    	$lastFacility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'STAMFORD ASC, LLC'
    	]);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'facility_id' => $lastFacility->id,
    			'address1' => '200 STAMFORD PL',
    			'city' => 'STAMFORD',
    			'zip' => '06902-6753'
    	]);
    }
    
    public function testDbFindOptionIdByKey()
    {
    	$key = 'ambulatory_surgical_center';
    	
    	$optionId = $this->importer->dbFindOptionIdByKey($key);
    	
    	$this->assertEquals(1, $optionId);
    }
    
    public function testDbFindOptionIdByKeyNotFound()
    {
    	$key = 'some_unknown_key';
    	 
    	$optionId = $this->importer->dbFindOptionIdByKey($key);
    	 
    	$this->assertNull($optionId);
    }
    
    public function testImport()
    {
    	$this->importer->import();
    }
}