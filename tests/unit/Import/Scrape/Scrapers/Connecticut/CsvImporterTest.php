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
    
    protected $categoryIds = [
    		'ambulatory_surgical_centers_recovery_care_centers' => 15,
    		'controlled_substances_practitioners_labs_manufacturers' => 18,
    		'healthcare_practitioners' => 21
    ];
    
    protected $optionIds = [
    		'ambulatory_surgical_center' => 36,
    		'manufacturers_of_drugs_cosmetics_and_medical_devices' => 42,
    		'acupuncturist' => 44
    ];
    
    protected function _before()
	{
    	$this->importData = [
    			[
    					'category' => 'ambulatory_surgical_centers_recovery_care_centers',
    					'option' => 'ambulatory_surgical_center',
    					'file_path' => codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
    			],
    			[
    					'category' => 'controlled_substances_practitioners_labs_manufacturers',
    					'option' => 'manufacturers_of_drugs_cosmetics_and_medical_devices',
    					'file_path' => codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/manufacturers_of_drugs_cosmetics_and_medical_devices.csv')
    			],
    			[
    					'category' => 'healthcare_practitioners',
    					'option' => 'acupuncturist',
    					'file_path' => codecept_data_dir('scrape/connecticut/csv/healthcare_practitioners/acupuncturist.csv')
    			]
    	];
    	$this->db = app('db')->connection();
    	$this->importer = new CsvImporter($this->importData, $this->db);
    	
    	/* insert categories */
    	$this->tester->haveRecord('ct_roster_categories', [
    			'id' => $this->categoryIds['ambulatory_surgical_centers_recovery_care_centers'],
    			'key' => 'ambulatory_surgical_centers_recovery_care_centers',
    			'name' => 'Ambulatory Surgical Centers/Recovery Care Centers',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    	
    	$this->tester->haveRecord('ct_roster_categories', [
    			'id' => $this->categoryIds['controlled_substances_practitioners_labs_manufacturers'],
    			'key' => 'controlled_substances_practitioners_labs_manufacturers',
    			'name' => 'Controlled Substances (Practitioners, Labs, Manufacturers)',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    	
    	$this->tester->haveRecord('ct_roster_categories', [
    			'id' => $this->categoryIds['healthcare_practitioners'],
    			'key' => 'healthcare_practitioners',
    			'name' => 'healthcare_practitioners name',
    	]);
    	
    	/* insert options */
    	$this->tester->haveRecord('ct_roster_options', [
    			'id' => $this->optionIds['ambulatory_surgical_center'],
    			'category_id' => $this->categoryIds['ambulatory_surgical_centers_recovery_care_centers'],
    			'key' => 'ambulatory_surgical_center',
    			'name' => 'Ambulatory Surgical Center',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    	
    	$this->tester->haveRecord('ct_roster_options', [
    			'id' => $this->optionIds['manufacturers_of_drugs_cosmetics_and_medical_devices'],
    			'category_id' => $this->categoryIds['controlled_substances_practitioners_labs_manufacturers'],
    			'key' => 'manufacturers_of_drugs_cosmetics_and_medical_devices',
    			'name' => 'Manufacturers of Drugs, Cosmetics and Medical Devices',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    	
    	$this->tester->haveRecord('ct_roster_options', [
    			'id' => $this->optionIds['acupuncturist'],
    			'category_id' => $this->categoryIds['healthcare_practitioners'],
    			'key' => 'acupuncturist',
    			'name' => 'acupuncturist name'
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
    
    public function testDbInsertFacilityRoster()
    {
    	$optionId = $this->optionIds['ambulatory_surgical_center'];
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
    	$timestamp = '2016-05-01 01:30:00';
    	 
    	$this->importer->dbInsertFacilityRoster($optionId, $data, $timestamp);
    	 
    	$facility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC'
    	]);
    	 
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['ambulatory_surgical_center'],
    			'facility_id' => $facility->id,
    			'person_id' => null,
    			'address1' => '360 BLOOMFIELD AVE STE 204',
    			'address2' => '',
    			'city' => 'WINDSOR',
    			'county' => '',
    			'state_code' => 'CT',
    			'zip' => '06095-2700',
    			'complete_address' => ''
    	]);
    }
    
    public function testDbInsertFacilityRosterWithExisting()
    {
    	// insert existing facility
    	$this->tester->haveRecord('ct_roster_facilities', [
    			'id' => 23,
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    	]);
    	
    	$optionId = $this->optionIds['ambulatory_surgical_center'];
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
    	$timestamp = '2016-05-01 01:30:00';
    	 
    	$this->importer->dbInsertFacilityRoster($optionId, $data, $timestamp);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['ambulatory_surgical_center'],
    			'facility_id' => 23,
    			'person_id' => null,
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
    	
    	$this->assertAmbulanceRecords();
    }
    
    public function testDbFindOptionIdByKey()
    {
    	$key = 'ambulatory_surgical_center';
    	
    	$optionId = $this->importer->dbFindOptionIdByKey($key);
    	
    	$this->assertEquals($this->optionIds['ambulatory_surgical_center'], $optionId);
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
    	
    	$this->assertAmbulanceRecords();
    	$this->assertDrugManufacturerRecords();
    	$this->assertAcupuncturistRecords();
    }
    
    public function testDbInsertPersonRoster()
    {
    	$optionId = $this->optionIds['acupuncturist'];
		$data = [
				'first_name' => 'THOMAS',
				'last_name' => 'RYAN',
				'address1' => '15 MAIN STREET',
				'address2' => '',
				'city' => 'EAST HAMPTON',
				'county' => 'Middlesex',
				'state_code' => 'CT',
				'zip' => 'O6424',
				'complete_address' => ''
    	];
		$timestamp = '2016-05-01 01:30:00';
    	
    	$this->importer->dbInsertPersonRoster($optionId, $data, $timestamp);
    	
    	$person = $this->tester->grabRecord('ct_roster_people', [
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN'
    	]);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['acupuncturist'],
    			'facility_id' => null,
    			'person_id' => $person->id,
    			'address1' => '15 MAIN STREET',
    			'address2' => '',
    			'city' => 'EAST HAMPTON',
    			'county' => 'Middlesex',
    			'state_code' => 'CT',
    			'zip' => 'O6424',
    			'complete_address' => ''
    	]);
    }
    
    public function testDbInsertPersonRosterWithExisting()
    {
    	// add person to db
    	$this->tester->haveRecord('ct_roster_people', [
    			'id' => 26,
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN'
    	]);
    	
    	$optionId = $this->optionIds['acupuncturist'];
    	$data = [
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN',
    			'address1' => '15 MAIN STREET',
    			'address2' => '',
    			'city' => 'EAST HAMPTON',
    			'county' => 'Middlesex',
    			'state_code' => 'CT',
    			'zip' => 'O6424',
    			'complete_address' => ''
    	];
    	$timestamp = '2016-05-01 01:30:00';
    	 
    	$this->importer->dbInsertPersonRoster($optionId, $data, $timestamp);
    	 
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['acupuncturist'],
    			'facility_id' => null,
    			'person_id' => 26,
    			'address1' => '15 MAIN STREET',
    			'address2' => '',
    			'city' => 'EAST HAMPTON',
    			'county' => 'Middlesex',
    			'state_code' => 'CT',
    			'zip' => 'O6424',
    			'complete_address' => ''
    	]);
    }
    
    public function testDbFindPersonIdByName()
    {
    	$this->tester->haveRecord('ct_roster_people', [
    			'id' => 26,
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN'
    	]);
    	
    	$firstName = 'THOMAS';
    	$lastName = 'RYAN';
    	
    	$personId = $this->importer->dbFindPersonIdByName($firstName, $lastName);
    	
    	$this->assertEquals(26, $personId);
    }
    
    public function testDbFindPersonIdByNameNotFound()
    {    	 
    	$firstName = 'THOMAS';
    	$lastName = 'RYAN';
    	 
    	$personId = $this->importer->dbFindPersonIdByName($firstName, $lastName);
    	 
    	$this->assertNull($personId);
    }
    
    public function testDbInsertPerson()
    {
    	$firstName = 'THOMAS';
    	$lastName = 'RYAN';
    	$timestamp = '2016-05-01 01:30:00';
    	
    	$personId = $this->importer->dbInsertPerson($firstName, $lastName, $timestamp);
    	
    	$this->tester->seeRecord('ct_roster_people', [
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00'
    	]);
    	
    	$this->assertTrue(is_numeric($personId));
    }
    
    protected function assertAmbulanceRecords()
    {
    	$firstFacility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC'
    	]);
    	 
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['ambulatory_surgical_center'],
    			'facility_id' => $firstFacility->id,
    			'address1' => '360 BLOOMFIELD AVE STE 204',
    			'city' => 'WINDSOR',
    			'zip' => '06095-2700'
    	]);
    	 
    	$lastFacility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'STAMFORD ASC, LLC'
    	]);
    	 
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['ambulatory_surgical_center'],
    			'facility_id' => $lastFacility->id,
    			'address1' => '200 STAMFORD PL',
    			'city' => 'STAMFORD',
    			'zip' => '06902-6753'
    	]);
    }
    
    protected function assertDrugManufacturerRecords()
    {	
    	$firstFacility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'JOLEN CREME BLEACH CORPORATION'
    	]);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['manufacturers_of_drugs_cosmetics_and_medical_devices'],
    			'facility_id' => $firstFacility->id,
    			'address1' => '25 WALLS DR',
    			'city' => 'FAIRFIELD',
    			'zip' => '06824-5156'
    	]);
    
    	$lastFacility = $this->tester->grabRecord('ct_roster_facilities', [
    			'name' => 'Esquire Gas Products'
    	]);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['manufacturers_of_drugs_cosmetics_and_medical_devices'],
    			'facility_id' => $lastFacility->id,
    			'address1' => '156 Spring St.',
    			'city' => 'Enfield',
    			'zip' => '06082'
    	]);
    }
    
    protected function assertAcupuncturistRecords()
    {
    	/* check first row */
    	$firstPerson = $this->tester->grabRecord('ct_roster_people', [
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN'
    	]);
    	
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['acupuncturist'],
    			'facility_id' => null,
    			'person_id' => $firstPerson->id,
    			'address1' => '15 MAIN STREET',
    			'address2' => '',
    			'city' => 'EAST HAMPTON',
    			'county' => 'Middlesex',
    			'state_code' => 'CT',
    			'zip' => 'O6424',
    			'complete_address' => ''
    	]);
    	
    	/* check last row */
    	$lastPerson = $this->tester->grabRecord('ct_roster_people', [
    			'first_name' => 'HAROLDO',
    			'last_name' => 'JEZLER'
    	]);
    	 
    	$this->tester->seeRecord('ct_rosters', [
    			'option_id' => $this->optionIds['acupuncturist'],
    			'facility_id' => null,
    			'person_id' => $lastPerson->id,
    			'address1' => '145 MILTON RD',
    			'address2' => '',
    			'city' => 'RYE',
    			'county' => 'Westchester',
    			'state_code' => 'NY',
    			'zip' => '1O58O-3812',
    			'complete_address' => ''
    	]);
    }
}