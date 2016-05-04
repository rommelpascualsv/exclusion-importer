<?php
namespace Console\Commands\Scrape;


use App\Console\Commands\Scrape\ScrapeConnecticut;
use App\Import\Scrape\Components\TestFilesystem;
use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use Goutte\Client;
use Laravel\Lumen\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;

class ScrapeConnecticutTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	
    /**
     * @var Application
     */
    protected $laravel;
    
    /**
     * @var Client
     */
    protected $client;
    
    /**
     * @var CategoryCollection
     */
    protected $categories;
    
    /**
     * @var ScrapeConnecticut
     */
    protected $command;
    
    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;
    
    protected function _before()
    {
    	$this->laravel = $this->getModule('Lumen')->app;
    	$this->filesystem = app('scrape_test_filesystem');
    	$this->command = new ScrapeConnecticut();
    	$this->command->setLaravel($this->laravel);
    }

    protected function _after()
    {
    }

    // tests
    public function testHandle()
    {
    	$this->refreshDirectory();
    	$this->setCsvDownloader([
    			'charities',
    			'healthcare_practitioners' => [
    					'acupuncturist',
    					'advanced_practice_registered_nurse'
    			]
    	]);
    	
    	$this->runCommand();
        
        $this->assertFileExists(codecept_output_dir('scrape/csv/connecticut/charities/paid_solicitors.csv'));
        $this->assertFileExists(codecept_output_dir('scrape/csv/connecticut/charities/public_charity.csv'));
        $this->assertFileExists(codecept_output_dir('scrape/csv/connecticut/healthcare_practitioners/acupuncturist.csv'));
        $this->assertFileExists(codecept_output_dir('scrape/csv/connecticut/healthcare_practitioners/advanced_practice_registered_nurse.csv'));
    }
    
    /* public function testHandleDlOneFile()
    {
    	$this->refreshDirectory();
    	$this->setCsvDownloader([
    			'healthcare_practitioners' => [
    					'acupuncturist'
    			]
    	]);
    	 
    	$this->runCommand();

    	$this->tester->assertScrapeFileExist('csv/connecticut/healthcare_practitioners/acupuncturist.csv');
    } */
    
    public function testHandleSkipMissingDownloadOptions()
    {
    	$this->refreshDirectory();
    	 
    	$this->setCsvDownloader([
    			'ambulatory_surgical_centers_recovery_care_centers',
    			'healthcare_practitioners' => [
    				'acupuncturist'
    			]
    	]);
    	 
    	$this->runCommand();
    	 
    	$this->assertFileExists(codecept_output_dir('scrape/csv/connecticut/ambulatory_surgical_centers_recovery_care_centers/ambulatory_surgical_center.csv'));
    	$this->assertFileExists(codecept_output_dir('scrape/csv/connecticut/healthcare_practitioners/acupuncturist.csv'));
    }
    
    protected function getCsvDownloader($options)
    {
    	/** @var Client $client */
    	$client = $this->laravel->make(Client::class);
    	/** @var CategoryCollection $categories */
    	$categories = $this->laravel->make(CategoryCollection::class);
    	
    	return new CsvDownloader($client, $categories->getOptions($options), $this->filesystem);
    }
    
    protected function setCsvDownloader(array $options)
    {
    	$csvDownloader = $this->getCsvDownloader($options);
    	
    	$this->laravel->instance(CsvDownloader::class, $csvDownloader);
    }
    
    protected function refreshDirectory()
    {
    	$dir = 'csv/connecticut';
    	 
    	$this->filesystem->deleteDir($dir);
    	$this->filesystem->createDir($dir);
    }
    
    protected function runCommand()
    {
    	$this->command->run(new ArrayInput([]), new ConsoleOutput());
    }
}