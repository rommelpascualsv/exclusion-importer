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
    	$this->fileService = new FileService();
    	$this->container = new Mockery\Container;
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
    	$this->assertEquals('test.csv', $file[0]->file_name);
    }
    
    /**
     * Test for the updateStateUrl method of FileService.
     * State prefix and new url are passed as parameters.
     *
     * Asserts that the number of updated records is one.
     * Asserts that the updated record is found in the database.
     */
    public function testUpdateStateUrl(){
    	$result = $this->fileService->updateStateUrl('az1', 'www.yahoo.com');
    	$this->tester->seeInDatabase('exclusion_lists', array('prefix' => 'az1', 'import_url' => 'www.yahoo.com'));
    }
    
    /**
     * Test for the getUrl method of FileService.
     * State prefix is passed as parameter.
     * 
     * Asserts not null for the url retrieved.
     * Asserts that url is equal to expected url.
     */
    public function testGetUrl(){
    	$url = $this->fileService->getUrl('az1');
    	$this->assertEquals('www.yahoo.com', $url[0]->import_url);
    }
    
    /**
     * Test for the isStateUpdateable method of FileService.
     * State prefix is passed as parameter.
     * 
     * Asserts that state is updateable.
     */
    public function testIsStateUpdateable(){
    	$result = $this->fileService->isStateUpdateable('az1');
    	$this->assertTrue($result);
    }
    
    /**
     * Test for the refreshRecords method of FileService. 
     */
    public function testRefreshRecordsFileNotSupported(){
//     	$result = $this->fileService->refreshRecords();
    	
    	$mock = $this->container->mock("App\Services\FileService[getUrls]");
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    	
    	$url = new stdClass();
    	$url->state_prefix = "az1";
    	$url->import_url = "http://yahoo.com";
    	
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	$mock->refreshRecords();
    }
    
    public function testRefreshRecordsPrefixExistsWillNotUpdate(){
    	$mock = $this->container->mock("App\Services\FileService[getUrls, getBlobOfFile]");
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    	 
    	$url = new stdClass();
    	$url->state_prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    	 
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	
    	$blobImgData = file_get_contents($url->import_url);
    	
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	
    	$mock->refreshRecords();
    }
    
    public function testRefreshRecordsPrefixExistsWillUpdate(){
    	$mock = $this->container->mock("App\Services\FileService[getUrls, getBlobOfFile]");
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    
    	$url = new stdClass();
    	$url->state_prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	 
    	$otherImportUrl = "http://www.yahoo.com";
    	$blobImgData = file_get_contents($otherImportUrl);
    	 
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	 
    	$mock->refreshRecords();
    	
    	//TODO add assert
    }
    
    public function testRefreshRecordsNoPrefixWillInsert(){
    	$mock = $this->container->mock("App\Services\FileService[getUrls, getBlobOfFile, isPrefixExists]");
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    
    	$url = new stdClass();
    	$url->state_prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	 
    	$blobImgData = file_get_contents($url->import_url);
    	 
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	$mock->shouldReceive("isPrefixExists")->andReturn(false);
    	 
    	$mock->refreshRecords();
    	
    	//TODO add assert
    }
    
}