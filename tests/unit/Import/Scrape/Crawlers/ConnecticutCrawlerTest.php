<?php
namespace Import\Scrape\Crawlers;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;


class ConnecticutCrawlerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->downloadPath = codecept_data_dir('Import/Scrape');
        $this->downloadFileName = 'Certified_Public_Accountant_Certificate.csv';
        $this->downloadFilePath = $this->downloadPath . '/' . $this->downloadFileName;
        $this->crawler = ConnecticutCrawler::create($this->downloadPath, $this->downloadFileName);
        
        if (file_exists($this->downloadFilePath)) {
        	unlink($this->downloadFilePath);
        }
    }

    protected function _after()
    {
    	unset($this->crawler);
    }
    
    public function testRequestUserAgent()
    {
    	$this->crawler->getMainCrawler();
    	
    	$requestServer = $this->crawler->getClient()->getRequest()->getServer();
    	
    	$this->assertNotEquals('Symfony2 BrowserKit', $requestServer['HTTP_USER_AGENT']);
    }
    
    public function testDownloadFile()
    {
        $this->crawler->downloadFile();
        
        $this->assertFileExists($this->downloadFilePath);
    }
}