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
        $path = codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv');
        $actual = $this->extractor->extractHeaders($path);
        $expected = [
            'facility name',
            'address',
            'city',
            'state',
            'zip',
            'license no.',
            'status',
            'effective date',
            'expiration date'
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testGetCsvLine()
    {
        $data = [
            'category' => 'ambulatory_surgical_centers_recovery_care_centers',
            'option' => 'ambulatory_surgical_center',
            'file_path' => codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
        ];

        $actual = $this->extractor->getCsvLine($data);
        $expected = [
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
                'file_path' => codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
            ],
            [
                'category' => 'child_day_care_licensing_program',
                'option' => 'child_day_care_centers_and_group_day_care_homes_closed_1_year',
                'file_path' => codecept_data_dir('scrape/connecticut/csv/child_day_care_licensing_program/child_day_care_centers_and_group_day_care_homes_closed_1_year.csv')
            ],
            [
                'category' => 'child_day_care_licensing_program',
                'option' => 'family_day_care_homes_total_by_date_active',
                'file_path' => codecept_data_dir('scrape/connecticut/csv/child_day_care_licensing_program/family_day_care_homes_total_by_date_active.csv')
            ],
            [
                'category' => 'infirmaries_clinics',
                'option' => 'family_planning_clinics',
                'file_path' => codecept_data_dir('scrape/connecticut/csv/infirmaries_clinics/family_planning_clinics.csv')
            ]
        ];
        $saveFilePath = $filesystem->getPath('extracted/connecticut/headers.csv');
        $this->extractor = new CsvHeadersExtractor($data, $saveFilePath);

        $actual = $this->extractor->extract()->getData();

        $expected = [
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
                'child_day_care_centers_and_group_day_care_homes_closed_1_year',
                'type',
                'close date',
                'license #',
                'name',
                'address',
                'city',
                'zip',
                'phone',
                'legal operator (lo)',
                'address (lo)',
                'city (lo)',
                'zip (lo)'
            ],
            [
                'child_day_care_licensing_program',
                'family_day_care_homes_total_by_date_active',
                'license #',
                'last name',
                'first name',
                'address',
                'city',
                'zip',
                'phone',
                'regular capacity',
                'school age capacity',
                'expiration date'
            ],
            [
                'infirmaries_clinics',
                'family_planning_clinics',
                'facility name',
                'address',
                'city',
                'state',
                'zip',
                'license no.',
                'status',
                'effective date',
                'expiration date'
            ]
        ];

        $this->assertEquals($expected, $actual);
    }

//    public function testCreate()
//    {
//    	$this->extractor = CsvHeadersExtractor::create($this->filesystem, 'extract_connecticut');
//    	
//    	$expected = [
//    			[
//    					'category' => 'ambulatory_surgical_centers_recovery_care_centers',
//    					'option' => 'ambulatory_surgical_center',
//    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
//    			],
//    			[
//    					'category' => 'child_day_care_licensing_program',
//    					'option' => 'child_day_care_centers_and_group_day_care_homes_closed_1_year',
//    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/child_day_care_licensing_program/child_day_care_centers_and_group_day_care_homes_closed_1_year.csv')
//    			],
//    			[
//    					'category' => 'child_day_care_licensing_program',
//    					'option' => 'family_day_care_homes_total_by_date_active',
//    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/child_day_care_licensing_program/family_day_care_homes_total_by_date_active.csv')
//    			],
//    			[
//    					'category' => 'infirmaries_clinics',
//    					'option' => 'family_planning_clinics',
//    					'file_path' => $this->filesystem->getPath('csv/extract_connecticut/infirmaries_clinics/family_planning_clinics.csv')
//    			]
//    	];
//    	
//    	$this->assertEquals($expected, $this->extractor->getFiles());
//    }

    public function testSave()
    {
        $this->filesystem->deleteDir('extracted/connecticut');

        $this->extractor = CsvHeadersExtractor::create($this->filesystem, 'extract_connecticut');

        $this->extractor->extract()->save();

        $this->tester->assertScrapeFileExist('extracted/connecticut/headers.csv');
    }

}
