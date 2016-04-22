<?php
namespace Import\Scrape\Scrapers\Connecticut\Extractors;


use App\Import\Scrape\Scrapers\Connecticut\Extractors\CsvHeadersExtractor;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;

class CsvHeadersExtractorTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;
    
    /**
     * @var CsvHeadersExtractor
     */
    protected $extractor;
    
    protected function _before()
    {
    	$this->filesystem = app('scrape_test_filesystem');
    	$this->extractor = new CsvHeadersExtractor();
    }

    protected function _after()
    {
    }
	
    public function testExtractHeaders()
    {
    	$path = app('scrape_test_filesystem')->getPath('csv/extract_connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv');
    	$actual = $this->extractor->extractHeaders($path);
    	$expected = [
    			'FACILITY NAME',
    			'ADDRESS',
    			'CITY',
    			'STATE',
    			'ZIP',
    			'LICENSE NO.',
    			'STATUS',
    			'EFFECTIVE DATE',
    			'EXPIRATION DATE'
    	];
    	
    	$this->assertEquals($expected, $actual);
    }
    
    public function testGetCsvLine()
    {
    	$data = [
    			'category' => 'ambulatory_surgical_centers_recovery_care_centers',
    			'option' => 'ambulatory_surgical_center',
    			'file_path' => app('scrape_test_filesystem')->getPath('csv/extract_connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
    	];
    	
    	$actual = $this->extractor->getCsvLine($data);
    	$expected = [
    			'ambulatory_surgical_centers_recovery_care_centers',
    			'ambulatory_surgical_center',
    			'FACILITY NAME',
    			'ADDRESS',
    			'CITY',
    			'STATE',
    			'ZIP',
    			'LICENSE NO.',
    			'STATUS',
    			'EFFECTIVE DATE',
    			'EXPIRATION DATE'
    	];
    	
    	$this->assertEquals($expected, $actual);
    }
    
    public function testExtract()
    {
    	/** @var ScrapeFilesystemInterface $filesystem */
    	$filesystem = app('scrape_test_filesystem');
    	$data = [
    			[
    					'category' => 'ambulatory_surgical_centers_recovery_care_centers',
    					'option' => 'ambulatory_surgical_center',
    					'file_path' => $filesystem->getPath('csv/extract_connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
				],
    			[
    					'category' => 'child_day_care_licensing_program',
    					'option' => 'child_day_care_centers_and_group_day_care_homes_closed_1_year',
    					'file_path' => $filesystem->getPath('csv/extract_connecticut/child_day_care_licensing_program/child_day_care_centers_and_group_day_care_homes_closed_1_year.csv')
    			],
    			[
    					'category' => 'child_day_care_licensing_program',
    					'option' => 'family_day_care_homes_total_by_date_active',
    					'file_path' => $filesystem->getPath('csv/extract_connecticut/child_day_care_licensing_program/family_day_care_homes_total_by_date_active.csv')
    			],
    			[
    					'category' => 'infirmaries_clinics',
    					'option' => 'family_planning_clinics',
    					'file_path' => $filesystem->getPath('csv/extract_connecticut/infirmaries_clinics/family_planning_clinics.csv')
    			]
    	];
    	$saveFilePath = $filesystem->getPath('extracted/connecticut/headers.csv'); 	
    	$this->extractor = new CsvHeadersExtractor($data, $saveFilePath);
    	
    	$actual = $this->extractor->extract()->getData();
    	
    	$expected = [
    			[
    					'ambulatory_surgical_centers_recovery_care_centers',
    					'ambulatory_surgical_center',
    					'FACILITY NAME',
    					'ADDRESS',
    					'CITY',
    					'STATE',
    					'ZIP',
    					'LICENSE NO.',
    					'STATUS',
    					'EFFECTIVE DATE',
    					'EXPIRATION DATE'
    			],
    			[
    					'child_day_care_licensing_program',
    					'child_day_care_centers_and_group_day_care_homes_closed_1_year',
    					'Type',
    					'Close Date',
    					'License #',
    					'Name',
    					'Address',
    					'City',
    					'Zip',
    					'Phone',
    					'Legal Operator (LO)',
    					'Address (LO)',
    					'City (LO)',
    					'Zip (LO)'
    			],
    			[
    					'child_day_care_licensing_program',
    					'family_day_care_homes_total_by_date_active',
    					'License #',
    					'Last Name',
    					'First Name',
    					'Address',
    					'City',
    					'Zip',
    					'Phone',
    					'Regular Capacity',
    					'School Age Capacity',
    					'Expiration Date'
    			],
    			[
    					'infirmaries_clinics',
    					'family_planning_clinics',
    					'FACILITY NAME',
    					'ADDRESS',
    					'CITY',
    					'STATE',
    					'ZIP',
    					'LICENSE NO.',
    					'STATUS',
    					'EFFECTIVE DATE',
    					'EXPIRATION DATE'		
    			]
    	];
    	
    	$this->assertEquals($expected, $actual);
    }
    
    public function testCreate()
    {
    	$this->extractor = CsvHeadersExtractor::create($this->filesystem, 'extract_connecticut');
    	
    	$expected = [
    			[
    					'category' => 'ambulatory_surgical_centers_recovery_care_centers',
    					'option' => 'ambulatory_surgical_center',
    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
    			],
    			[
    					'category' => 'child_day_care_licensing_program',
    					'option' => 'child_day_care_centers_and_group_day_care_homes_closed_1_year',
    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/child_day_care_licensing_program/child_day_care_centers_and_group_day_care_homes_closed_1_year.csv')
    			],
    			[
    					'category' => 'child_day_care_licensing_program',
    					'option' => 'family_day_care_homes_total_by_date_active',
    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/child_day_care_licensing_program/family_day_care_homes_total_by_date_active.csv')
    			],
    			[
    					'category' => 'infirmaries_clinics',
    					'option' => 'family_planning_clinics',
    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/infirmaries_clinics/family_planning_clinics.csv')
    			]
    	];
    	
    	$this->assertEquals($expected, $this->extractor->getFiles());
    }
    
    public function testSave()
    {
    	$this->filesystem->deleteDir('extracted/connecticut');
    	
    	$this->extractor = CsvHeadersExtractor::create($this->filesystem, 'extract_connecticut');
    	
    	$this->extractor->extract()->save();
    	
    	$this->tester->assertScrapeFileExist('extracted/connecticut/headers.csv');
    }
}