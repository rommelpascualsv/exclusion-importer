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
    
    protected $licenseTypeIds = [
    		'ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center' => 36,
    		'controlled_substances_practitioners_labs_manufacturers.manufacturers_of_drugs_cosmetics_and_medical_devices' => 42,
    		'controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories' => 43,
    		'healthcare_practitioners.acupuncturist' => 44
    ];
    
    protected function _before()
	{
    	$this->importData = $this->getImportData();
    	$this->db = app('db')->connection();
    	$this->importer = new CsvImporter($this->importData, $this->db);
    	
    	$this->seedCategories();
    	$this->seedLicenseTypes();
    }

    protected function _after()
    {
    }
    
    protected function getImportData()
    {
        return [
            [
                'category' => 'ambulatory_surgical_centers_recovery_care_centers',
                'option' => 'ambulatory_surgical_center',
                'file_path' => codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
            ],
            [
                'category' => 'controlled_substances_practitioners_labs_manufacturers',
                'option' => 'controlled_substance_laboratories',
                'file_path' => codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/controlled_substance_laboratories.csv')
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
    }
    
    protected function seedCategories()
    {
        $this->tester->haveRecord('ct_license_categories', [
            'id' => $this->categoryIds['ambulatory_surgical_centers_recovery_care_centers'],
            'key' => 'ambulatory_surgical_centers_recovery_care_centers',
            'name' => 'Ambulatory Surgical Centers/Recovery Care Centers'
        ]);
         
        $this->tester->haveRecord('ct_license_categories', [
            'id' => $this->categoryIds['controlled_substances_practitioners_labs_manufacturers'],
            'key' => 'controlled_substances_practitioners_labs_manufacturers',
            'name' => 'Controlled Substances (Practitioners, Labs, Manufacturers)'
        ]);
         
        $this->tester->haveRecord('ct_license_categories', [
            'id' => $this->categoryIds['healthcare_practitioners'],
            'key' => 'healthcare_practitioners',
            'name' => 'Healthcare Practitioners'
        ]);
    }
    
    protected function seedLicenseTypes()
    {
        $table = 'ct_license_types';
        
        $this->tester->haveRecord($table, [
            'id' => $this->licenseTypeIds['ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center'],
            'ct_license_categories_id' => $this->categoryIds['ambulatory_surgical_centers_recovery_care_centers'],
            'key' => 'ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center',
            'name' => 'Ambulatory Surgical Center'
        ]);
         
        $this->tester->haveRecord($table, [
            'id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.manufacturers_of_drugs_cosmetics_and_medical_devices'],
            'ct_license_categories_id' => $this->categoryIds['controlled_substances_practitioners_labs_manufacturers'],
            'key' => 'controlled_substances_practitioners_labs_manufacturers.manufacturers_of_drugs_cosmetics_and_medical_devices',
            'name' => 'Manufacturers of Drugs, Cosmetics and Medical Devices'
        ]);
         
        $this->tester->haveRecord($table, [
            'id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories'],
            'ct_license_categories_id' => $this->categoryIds['controlled_substances_practitioners_labs_manufacturers'],
            'key' => 'controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories',
            'name' => 'Controlled Substance Laboratories'
        ]);
         
        $this->tester->haveRecord($table, [
            'id' => $this->licenseTypeIds['healthcare_practitioners.acupuncturist'],
            'ct_license_categories_id' => $this->categoryIds['healthcare_practitioners'],
            'key' => 'healthcare_practitioners.acupuncturist',
            'name' => 'Acupuncturist'
        ]);
    }
    
    public function testDbFindLicenseTypeIdByKey()
    {
        $key = 'ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center';
         
        $id = $this->importer->dbFindLicenseTypeIdByKey($key);
         
        $this->assertEquals($this->licenseTypeIds['ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center'], $id);
    }
    
    public function testDbFindLicenseTypeIdByKeyNotFound()
    {
        $key = 'some_unknown_key';
    
        $id = $this->importer->dbFindLicenseTypeIdByKey($key);
    
        $this->assertNull($id);
    }
    
    public function testDbInsertRoster()
    {
        $licenseTypeId = $this->licenseTypeIds['ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center'];
        $data = [
            'first_name' => '',
            'last_name' => '',
            'business_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'address1' => '360 BLOOMFIELD AVE STE 204',
            'address2' => '',
            'city' => 'WINDSOR',
            'county' => '',
            'state' => 'CT',
            'zip' => '06095-2700',
            'license_no' => '321',
            'license_effective_date' => '2008-10-30',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => ''
        ];
        $timestamp = '2016-05-01 01:30:00';
    
        $this->importer->dbInsertRoster($licenseTypeId, $data, $timestamp);
    
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center'],
            'first_name' => '',
            'last_name' => '',
            'business_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'address1' => '360 BLOOMFIELD AVE STE 204',
            'address2' => '',
            'city' => 'WINDSOR',
            'county' => '',
            'state' => 'CT',
            'zip' => '06095-2700',
            'license_no' => '321',
            'license_effective_date' => '2008-10-30',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => '',
            'created_at' => '2016-05-01 01:30:00',
            'updated_at' => '2016-05-01 01:30:00'
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
    
    public function testImport()
    {
        $this->importer->import();
         
        $this->assertAmbulanceRecords();
        $this->assertControlledSubstanceLaboratoriesRecords();
        $this->assertDrugManufacturerRecords();
        $this->assertAcupuncturistRecords();
    }
    
    protected function assertAmbulanceRecords()
    {
        // first row
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center'],
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
            'address1' => '360 BLOOMFIELD AVE STE 204',
            'address2' => null,
            'city' => 'WINDSOR',
            'county' => null,
            'state' => 'CT',
            'zip' => '06095-2700',
            'license_no' => '321',
            'license_effective_date' => '2008-10-30',
            'license_expiration_date' => '2016-09-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]);
        
        // last row
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center'],
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'STAMFORD ASC, LLC',
            'address1' => '200 STAMFORD PL',
            'address2' => null,
            'city' => 'STAMFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06902-6753',
            'license_no' => '351',
            'license_effective_date' => '2016-03-31',
            'license_expiration_date' => '2017-12-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]);
    }
    
    protected function assertControlledSubstanceLaboratoriesRecords()
    {
        // first row, person record
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories'],
            'first_name' => 'ROBERT',
            'last_name' => 'MALISON',
            'business_name' => null,
            'address1' => '34 PARK ST',
            'address2' => null,
            'city' => 'NEW HAVEN',
            'county' => null,
            'state' => 'CT',
            'zip' => '06519-1109',
            'license_no' => 'CSL.0000432',
            'license_effective_date' => '2016-02-01',
            'license_expiration_date' => '2017-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]);
         
        // row 16, facility record
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories'],
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'NARCOTICS CONTROL OFFICER',
            'address1' => '5 RESEARCH PKWY',
            'address2' => null,
            'city' => 'WALLINGFORD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06492-1951',
            'license_no' => 'CSL.0000252',
            'license_effective_date' => '2016-02-01',
            'license_expiration_date' => '2017-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]);
         
        // last row, person record
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories'],
            'first_name' => 'SREEGANGA',
            'last_name' => 'CHANDRA',
            'business_name' => null,
            'address1' => '295 CONGRESS AVE BCMM 149',
            'address2' => null,
            'city' => 'NEW HAVEN',
            'county' => null,
            'state' => 'CT',
            'zip' => '06519-1418',
            'license_no' => 'CSL.0001141',
            'license_effective_date' => '2016-03-08',
            'license_expiration_date' => '2017-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]); 
    }
    
    protected function assertDrugManufacturerRecords()
    {	
        // first row
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.manufacturers_of_drugs_cosmetics_and_medical_devices'],
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'JOLEN CREME BLEACH CORPORATION',
            'address1' => '25 WALLS DR',
            'address2' => null,
            'city' => 'FAIRFIELD',
            'county' => null,
            'state' => 'CT',
            'zip' => '06824-5156',
            'license_no' => 'CSM.0000025',
            'license_effective_date' => '2015-07-01',
            'license_expiration_date' => '2016-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]);
        
        // last row
        $this->tester->seeRecord('ct_rosters', [
            'ct_license_types_id' => $this->licenseTypeIds['controlled_substances_practitioners_labs_manufacturers.manufacturers_of_drugs_cosmetics_and_medical_devices'],
            'first_name' => null,
            'last_name' => null,
            'business_name' => 'Esquire Gas Products',
            'address1' => '156 Spring St.',
            'address2' => null,
            'city' => 'Enfield',
            'county' => null,
            'state' => 'CT',
            'zip' => '06082',
            'license_no' => 'CSM.0000893',
            'license_effective_date' => '2016-01-27',
            'license_expiration_date' => '2016-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ]);
    }
    
    protected function assertAcupuncturistRecords()
    {
    	// first row
    	$this->tester->seeRecord('ct_rosters', [
    	    'ct_license_types_id' => $this->licenseTypeIds['healthcare_practitioners.acupuncturist'],
    	    'first_name' => 'THOMAS',
    	    'last_name' => 'RYAN',
    	    'business_name' => null,
    	    'address1' => '15 MAIN STREET',
            'address2' => null,
            'city' => 'EAST HAMPTON',
            'county' => 'Middlesex',
            'state' => 'CT',
            'zip' => 'O6424',
            'license_no' => '000001',
            'license_effective_date' => '1996-04-12',
            'license_expiration_date' => '2016-08-31',
            'license_status' => 'ACTIVE',
    	    'license_status_reason' => 'CURRENT'
    	]);
    	
    	// last row    	
    	$this->tester->seeRecord('ct_rosters', [
    	    'ct_license_types_id' => $this->licenseTypeIds['healthcare_practitioners.acupuncturist'],
    	    'first_name' => 'HAROLDO',
    	    'last_name' => 'JEZLER',
    	    'business_name' => null,
    	    'address1' => '145 MILTON RD',
    	    'address2' => null,
    	    'city' => 'RYE',
    	    'county' => 'Westchester',
    	    'state' => 'NY',
    	    'zip' => '1O58O-3812',
    	    'license_no' => '000667',
    	    'license_effective_date' => '2016-04-19',
    	    'license_expiration_date' => '2017-12-31',
    	    'license_status' => 'ACTIVE',
    	    'license_status_reason' => 'PRINT LICENSE'
    	]);
    }
}