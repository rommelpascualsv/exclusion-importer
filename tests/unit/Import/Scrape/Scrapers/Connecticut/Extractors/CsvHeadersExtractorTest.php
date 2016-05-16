<?php

namespace Import\Scrape\Scrapers\Connecticut\Extractors;

use App\Import\Scrape\Scrapers\Connecticut\Extractors\CsvHeadersExtractor;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use League\Csv\Reader;

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
        $this->filesData = $this->getFilesData();
        $this->saveFilePath = $this->filesystem->getPath('extracted/connecticut/headers.csv');
        $this->extractor = new CsvHeadersExtractor($this->filesData, $this->saveFilePath);
    }

    protected function _after()
    {
    }
    
    /**
     * Get files parameter
     */
    protected function getFilesData()
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
            ]
        ];
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
                'controlled_substances_practitioners_labs_manufacturers',
                'controlled_substance_laboratories',
                'contact first name',
                'contact last name',
                'business name',
                'address',
                'city',
                'state',
                'zip',
                'license number',
                'status',
                'effective date',
                'expiration date'
            ],
            
        ];

        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Test if file is saved in the correct path and has the correct contents
     */
    public function testSave()
    {
        $this->resetSave();   
    
        $this->extractor->extract()->save();
        
        $this->assertFileExists(codecept_output_dir('scrape/extracted/connecticut/headers.csv'));
        
        /* assert file contents */
        $reader = Reader::createFromPath(codecept_output_dir('scrape/extracted/connecticut/headers.csv'));
        
        $this->assertSame(
            $this->extractor->getData(),
            $reader->fetchAll()
        );
    }
    
    protected function resetSave()
    {
        // delete folder
        if (is_dir($this->filesystem->getPath('extracted/connecticut'))) {
            $this->filesystem->deleteDir('extracted/connecticut');
        }
    }
    
    /**
     * Test for extracting all headers in a directory
     */
    public function testCreate()
    {
        $this->prepareCreate();
        
        $dirPath = 'connecticut/csv';
        $this->extractor = CsvHeadersExtractor::create($this->filesystem, $dirPath);
        
        // assert files data generated from tests/_output/scrape/connecticut/csv
        $expectedFilesData = [
            [
                'category' => 'ambulatory_surgical_centers_recovery_care_centers',
                'option' => 'ambulatory_surgical_center',
                'file_path' => codecept_output_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
            ],
            [
                'category' => 'controlled_substances_practitioners_labs_manufacturers',
                'option' => 'controlled_substance_laboratories',
                'file_path' => codecept_output_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/controlled_substance_laboratories.csv')
            ],
            [
                'category' => 'controlled_substances_practitioners_labs_manufacturers',
                'option' => 'manufacturers_of_drugs_cosmetics_and_medical_devices',
                'file_path' => codecept_output_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/manufacturers_of_drugs_cosmetics_and_medical_devices.csv')
            ],
            [
                'category' => 'healthcare_practitioners',
                'option' => 'acupuncturist',
                'file_path' => codecept_output_dir('scrape/connecticut/csv/healthcare_practitioners/acupuncturist.csv')
            ]
        ];
        
        $this->assertSame($expectedFilesData, $this->extractor->getFiles());
        
        // assert save file path
        $this->assertSame(
            $this->filesystem->getPath('extracted/connecticut/headers.csv'),
            $this->extractor->getSaveFilePath()
        );
    }
    
    protected function prepareCreate()
    {
        // delete base dir
        if (is_dir($this->filesystem->getPath('connecticut/csv'))) {
            $this->filesystem->deleteDir('connecticut/csv');
        }
        
        // copy csvs to output
        $csvs = [
            codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv'),
            codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/controlled_substance_laboratories.csv'),
            codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/manufacturers_of_drugs_cosmetics_and_medical_devices.csv'),
            codecept_data_dir('scrape/connecticut/csv/healthcare_practitioners/acupuncturist.csv')
        ];
        
        foreach ($csvs as $value) {
            $outputFilePath = str_replace('_data', '_output', $value);
            $outputFileDir = dirname($outputFilePath);
        
            if (! is_dir($outputFileDir)) {
                mkdir($outputFileDir, 0755, true);
            }
            copy($value, $outputFilePath);
        }
    }
}
