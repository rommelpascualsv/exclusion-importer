<?php
namespace Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use Codeception\TestCase\Test;
use Goutte\Client;
use UnitTester;

class CsvDownloaderTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;
    
    /**
     * @var CategoryCollection 
     */
    protected $categories;
    
    /**
     * @var CsvDownloader
     */
    protected $csvDownloader;
    
    protected function _before()
    {
        $this->client = app(Client::class);
        $this->categories = app(CategoryCollection::class);
        $this->filesystem = app('scrape_test_filesystem');
    }
    
    /**
     * Get csv downloader
     * 
     * @param array $options
     * @return CsvDownloader
     */
    protected function getCsvDownloader(array $options)
    {
        $optionCollection = $this->categories->getOptions($options);
        
        return new CsvDownloader(
            $this->client,
            $optionCollection,
            $this->filesystem
        );
    }
    
    protected function _after()
    {
    }
    
    /**
     * Test download method if it downloads files to the correct path
     */
    public function testDownload()
    {
        $this->resetDownloadDirectory();
        
        $this->csvDownloader = $this->getCsvDownloader([
            'charities',
            'healthcare_practitioners' => [
                'acupuncturist',
                'advanced_practice_registered_nurse'
            ]
        ]);
        
        $this->csvDownloader->download();
        
        $this->assertFileExists(
            codecept_output_dir('scrape/csv/connecticut/charities/paid_solicitors.csv')
        );
        $this->assertFileExists(
            codecept_output_dir('scrape/csv/connecticut/charities/public_charity.csv')
        );
        $this->assertFileExists(
            codecept_output_dir('scrape/csv/connecticut/healthcare_practitioners/acupuncturist.csv')
        );
        $this->assertFileExists(
            codecept_output_dir('scrape/csv/connecticut/healthcare_practitioners/advanced_practice_registered_nurse.csv')
        );
    }
    
    /**
     * Test download method if it skips missing download options
     */
    public function testDownloadSkipMissingDownloadOptions()
    {
    	$this->resetDownloadDirectory();
    	 
        $this->csvDownloader = $this->getCsvDownloader([
            'ambulatory_surgical_centers_recovery_care_centers',
            'healthcare_practitioners' => [
                'acupuncturist'
            ]
        ]);
        
    	$this->csvDownloader->download();
    	 
    	$this->assertFileExists(
            codecept_output_dir('scrape/csv/connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv')
        );
    	$this->assertFileExists(
            codecept_output_dir('scrape/csv/connecticut/healthcare_practitioners/acupuncturist.csv')
        );
    }
    
    /**
     * Helper to reset download directory
     */
    protected function resetDownloadDirectory()
    {
        $this->filesystem->deleteDir('csv/connecticut');
    }
}