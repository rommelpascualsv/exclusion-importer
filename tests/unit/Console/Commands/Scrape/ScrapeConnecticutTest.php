<?php
namespace Console\Commands\Scrape;


use App\Console\Commands\Scrape\ScrapeConnecticut;
use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use App\Import\Scrape\Components\TestFilesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Laravel\Lumen\Application;
use Codeception\Lib\Console\Output;
use Symfony\Component\Console\Output\ConsoleOutput;

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
     * @var TestFilesystem
     */
    protected $filesystem;
    
    protected function _before()
    {
    	$this->laravel = $this->getModule('Lumen')->app;
    	$this->filesystem = $this->laravel->make(TestFilesystem::class);;
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
    	
    	$this->assertFilesDownloaded([
    			'Paid_Solicitors.csv',
    			'Public_Charity.csv',
    			'Acupuncturist.csv',
    			'Advanced_Practice_Registered_Nurse.csv'
    	]);
    }
    
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
    	 
    	$this->assertFilesDownloaded([
    			'Ambulatory_Surgical_Center.csv',
    			'Acupuncturist.csv'
    	]);
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
    
    protected function assertFilesDownloaded($files)
    {
    	foreach ($files as $file) {
    		$this->assertFileExists(codecept_data_dir('scrape/csv/connecticut/' . $file));
    	}
    }
}