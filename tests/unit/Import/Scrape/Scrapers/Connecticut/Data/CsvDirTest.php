<?php
namespace Import\Scrape\Scrapers\Connecticut\Data;


use App\Import\Scrape\Scrapers\Connecticut\Data\CsvDir;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;

class CsvDirTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;
    
    protected function _before()
    {
        $this->filesystem = app('scrape_test_filesystem');
    }

    protected function _after()
    {
    }
    
    // TESTS
    
    /**
     * Test if the csv directory data retrieved from CsvDirTest::getDataFromFilesystem 
     * is correct
     */
    public function testGetDataFromFilesystem()
    {
        $this->deleteCsvs();
        $this->copyCsvsToOutput();
        
        $csvDirData = CsvDir::getDataFromFilesystem(
            app('scrape_test_filesystem'),
            'connecticut/csv'
        );
        
        $expectedCsvDirData = [
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
                'category' => 'healthcare_practitioners',
                'option' => 'acupuncturist',
                'file_path' => codecept_output_dir('scrape/connecticut/csv/healthcare_practitioners/acupuncturist.csv')
            ]
        ];
        
        $this->assertSame($csvDirData, $expectedCsvDirData);
    }
    
    // HELPER FUNCTIONS
    
    protected function copyCsvsToOutput()
    {
        $csvs = [
            codecept_data_dir('scrape/connecticut/csv/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv'),
            codecept_data_dir('scrape/connecticut/csv/controlled_substances_practitioners_labs_manufacturers/controlled_substance_laboratories.csv'),
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
    
    protected function deleteCsvs()
    {
        $this->filesystem->deleteDir('connecticut');
    }
}