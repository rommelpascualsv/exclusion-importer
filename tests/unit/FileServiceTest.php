<?php

use App\Services\FileService;

/**
 * Unit test class for File Service.
 *
 */
class FileServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
	protected $tester;
	
    protected $fileService;
    
    protected $container;
    
    /**
     * Instantiate the FileService.
     */
    protected function _before()
    {
    	$this->container = new Mockery\Container;
    	$service = $this->container->mock("App\Services\Contracts\ImportServiceInterface");
    	$this->fileService = new FileService($service);
    }

    protected function _after()
    {
    }

    /**
     * Test for the getFile method of FileService.
     * State prefix is passed as parameter. 
     * 
     * Asserts not null for the file retrieved.
     * Asserts if filename is equal to expected name.
     */
    public function testGetFile(){
    	$file = $this->fileService->getFile('ak1');
    	$this->assertNotNull($file);
    }
    
    /**
     * Test for the refreshRecords method of FileService. 
     */
    public function testRefreshRecordsFileNotSupported(){
    	$importService = $this->container->mock("App\Services\Contracts\ImportServiceInterface");
    	$mock = $this->container->mock("App\Services\FileService[getUrls]", array($importService));
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    	
    	$url = new stdClass();
    	$url->prefix = "az1";
    	$url->import_url = "http://yahoo.com";
    	
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	$mock->refreshRecords();
    }
    
    public function testRefreshRecordsPrefixExistsWillNotUpdate(){
    	$importService = $this->container->mock("App\Services\Contracts\ImportServiceInterface");
    	$mock = $this->container->mock("App\Services\FileService[getUrls, getBlobOfFile]", array($importService));
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    	 
    	$url = new stdClass();
    	$url->prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    	 
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	
    	$blobImgData = file_get_contents($url->import_url);
    	
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	
    	$mock->refreshRecords();
    }
    
    public function testRefreshRecordsPrefixExistsWillUpdate(){
    	$importService = $this->container->mock("App\Services\Contracts\ImportServiceInterface");
    	$mock = $this->container->mock("App\Services\FileService[getUrls, getBlobOfFile]", array($importService));
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    
    	$url = new stdClass();
    	$url->prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	 
    	$otherImportUrl = "http://www.yahoo.com";
    	$blobImgData = file_get_contents($otherImportUrl);
    	 
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	 
    	$mock->refreshRecords();
    	
    	$this->tester->seeInDatabase('files', array('state_prefix' => 'wy1'));
    }
    
    public function testRefreshRecordsNoPrefixWillInsert(){
    	$importService = $this->container->mock("App\Services\Contracts\ImportServiceInterface");
    	$mock = $this->container->mock("App\Services\FileService[getUrls, getBlobOfFile, isPrefixExists]", array($importService));
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    
    	$url = new stdClass();
    	$url->prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	 
    	$blobImgData = file_get_contents($url->import_url);
    	 
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	$mock->shouldReceive("isPrefixExists")->andReturn(false);
    	 
    	$mock->refreshRecords();
    	
    	$this->tester->seeInDatabase('files', array('state_prefix' => 'wy1'));
    }
    
}