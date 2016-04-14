<?php
namespace Import\Scrape\Crawlers;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use App\Import\Scrape\Data\ConnecticutCategories;


class ConnecticutCrawlerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    	$options = [
    			ConnecticutCategories::OPT_ACCOUNTANT_CERTIFICATE,
    			ConnecticutCategories::OPT_ACCOUNTANT_FIRM_PERMIT,
    			ConnecticutCategories::OPT_ANIMAL_IMPORTERS,
    			ConnecticutCategories::OPT_AMBULATORY_SURGICAL_CENTER
    	];
    	
        $this->downloadPath = codecept_data_dir('Import/Scrape');
        $this->crawler = ConnecticutCrawler::create($this->downloadPath, $options);
    }

    protected function _after()
    {
    	unset($this->crawler);
    }
    
    public function testCreate()
    {
    	/* test options */
    	$options = $this->crawler->getOptions();
    	
    	$this->assertCount(4, $options);
    	$this->assertArrayHasKey('label', $options[0]);
    }
    
    public function testGetXpath()
    {
    	$xpath = ConnecticutCrawler::getXpath('download_options', 'column', 'Animal Importers');
    	
    	$this->assertEquals('//td[text() = "Animal Importers"]', $xpath);
    }
    
    public function testRequestUserAgent()
    {
    	/* $this->crawler->getMainCrawler();
    	
    	$requestServer = $this->crawler->getClient()->getRequest()->getServer();
    	
    	$this->assertNotEquals('Symfony2 BrowserKit', $requestServer['HTTP_USER_AGENT']); */
    }
    
    public function testDownloadFile()
    {
        $this->crawler->downloadFiles();
        
        $this->assertFileDownloaded('Ambulatory_Surgical_Center.csv');
        $this->assertFileDownloaded('Animal_Importers.csv');
        $this->assertFileDownloaded('Certified_Public_Accountant_Certificate.csv');
        $this->assertFileDownloaded('Certified_Public_Accountant_Firm_Permit.csv');
    }
    
    protected function assertFileDownloaded($fileName)
    {
    	$this->assertFileExists($this->downloadPath . '/' . $fileName);
    }
}