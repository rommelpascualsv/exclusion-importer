<?php
namespace Import\Scrape\Scrapers\Connecticut\Extractors;


use App\Import\Scrape\Scrapers\Connecticut\Extractors\HeadersOrganizer;
use League\Csv\Reader;

class HeadersOrganizerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var HeadersOrganizer
     */
    protected $organizer;
    
    /**
     * @var string
     */
    protected $savePath;
    
    protected function _before()
    {
    	$this->data = [
    			[
    					'ambulatory_surgical_centers_recovery_care_centers',
    					'ambulatory_surgical_center',
    					'facility name',
    					'address',
    					'city',
    					'state',
    					'zip',
    					'license no.',
    					'status',
    					'effective date',
    					'expiration date'
    			],
    			[
    					'child_day_care_licensing_program',
						'child_day_care_centers_and_group_day_care_homes_total_by_date_active',
						'type',
						'license #',
						'name',
						'address',
						'city',
						'zip',
						'phone',
						'ages served',
						'total capacity',
						'capacity under 3',
						'expiration date',
						'legal operator (lo)',
						'address (lo)',
						'city (lo)',
						'zip (lo)'
    			],
    			[
    					'controlled_substances_practitioners_labs_manufacturers',
    					'manufacturers_of_drugs_cosmetics_and_medical_devices',
    					'manufacturer name',
    					'address',
    					'city',
    					'state',
    					'zip',
    					'registration',
    					'status',
    					'effective date',
    					'expiration date'
    			]
    			
    	];
    	$this->savePath = codecept_output_dir('scrape/extracted/connecticut/organized-headers.csv');
    	$this->organizer = new HeadersOrganizer($this->data, $this->savePath);
    }

    protected function _after()
    {
    }

    // tests
    public function testOrganize()
    {    	
    	$self = $this->organizer->organize();
    	$expected = [
    			[
    					'CATEGORY',
    					'OPTION',
    					'facility name',
    					'address',
    					'city',
    					'state',
    					'zip',
    					'license no.',
    					'status',
    					'effective date',
    					'expiration date',
						'type',
						'license #',
						'name',
						'phone',
						'ages served',
						'total capacity',
						'capacity under 3',
						'legal operator (lo)',
						'address (lo)',
						'city (lo)',
						'zip (lo)',
						'manufacturer name',
						'registration'
    			],
    			[
    					'ambulatory_surgical_centers_recovery_care_centers',
    					'ambulatory_surgical_center',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES'
    			],
    			[
    					'child_day_care_licensing_program',
    					'child_day_care_centers_and_group_day_care_homes_total_by_date_active',
    					'',
    					'YES',
    					'YES',
    					'',
    					'YES',
    					'',
    					'',
    					'',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'YES'
    			],
    			[
    					'controlled_substances_practitioners_labs_manufacturers',
    					'manufacturers_of_drugs_cosmetics_and_medical_devices',
    					'',
    					'YES',
    					'YES',
    					'YES',
    					'YES',
    					'',
    					'YES',
    					'YES',
    					'YES',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'',
    					'YES',
    					'YES'
    			]
    	];
    	
    	$this->assertEquals($this->organizer, $self);
    	$this->assertEquals(
    		$expected,
    		$this->organizer->getResult()
    	);
    }
    
    public function testSave()
    {
    	$this->organizer->organize()->save();
    	
    	$this->assertFileExists($this->savePath);
    	
    	$reader = Reader::createFromPath($this->savePath);
    	
    	$this->assertEquals(
    			$this->organizer->getResult(),
    			$reader->fetchAll()
    	);
    }
}