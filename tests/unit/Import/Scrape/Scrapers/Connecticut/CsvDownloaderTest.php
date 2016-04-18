<?php
namespace Import\Scrape\Scrapers\Connecticut;


use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use App\Import\Scrape\Components\TestFilesystem;
use App\Import\Scrape\Components\FilesystemInterface;

class CsvDownloaderTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    	/** @var Client $client */
    	$this->client = $this->getModule('Lumen')->app->make(Client::class);
    	/** @var CategoryCollection $categories */
    	$this->categories = $this->getModule('Lumen')->app->make(CategoryCollection::class);
    	/** @var FilesystemInterface $categories */
    	$this->filesystem = $this->getModule('Lumen')->app->make(TestFilesystem::class);
    }

    protected function _after()
    {
    }

    // tests
    public function testDownload()
    {	
    	$this->refreshDirectory();
    	
    	$options = $this->categories->getOptions([
    			'charities',
    			'healthcare_practitioners' => [
    					'acupuncturist',
    					'advanced_practice_registered_nurse'
    			]
    	]);
    	
    	$this->downloader = new CsvDownloader($this->client, $options, $this->filesystem);
    	
    	$this->downloader->download();
    	
    	$this->assertFilesDownloaded([
    			'Paid_Solicitors.csv',
    			'Public_Charity.csv',
    			'Acupuncturist.csv',
    			'Advanced_Practice_Registered_Nurse.csv'
    	]);
    }
    
    protected function refreshDirectory()
    {
    	$dir = 'csv/connecticut';
    	
    	$this->filesystem->deleteDir($dir);
    	$this->filesystem->createDir($dir);
    }
    
    protected function assertFilesDownloaded($files)
    {
    	foreach ($files as $file) {
    		$this->assertFileExists(codecept_data_dir('scrape/csv/connecticut/' . $file));
    	}
    }
}