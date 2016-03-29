<?php

use App\Services\ImportFileService;

/**
 * Unit test class for File Service.
 *
 */
class ImportFileServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
	protected $tester;
	
    protected $importFileService;
    
    protected $container;
    
    /**
     * Instantiate the ImportFileService.
     */
    protected function _before()
    {
    	$this->container = new Mockery\Container;
    	$this->importFileService = new ImportFileService();
    }

    protected function _after()
    {
    	$this->container->mockery_close();
    }

    /**
     * Test for the getFile method of ImportFileService.
     * State prefix is passed as parameter. 
     * 
     * Asserts not null for the file retrieved.
     * Asserts if filename is equal to expected name.
     */
    public function testGetFile()
    {
    	$file = $this->importFileService->getFile('ak1');
    	$this->assertNotNull($file);
    }
    
    /**
     * Test for the refreshRecords method of ImportFileService. 
     */
    public function testRefreshRecordsFileNotSupported()
    {
    	$mock = $this->container->mock("App\Services\ImportFileService[getUrls]");
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    	
    	$url = new stdClass();
    	$url->prefix = "az1";
    	$url->import_url = "http://yahoo.com";
    	
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	$mock->refreshRecords();
    }
    
    public function testRefreshRecordsPrefixExistsWillNotUpdate()
    {
    	$mock = $this->container->mock("App\Services\ImportFileService[getUrls, getBlobOfFile]");
    	$mock = $mock->shouldAllowMockingProtectedMethods();
    	 
    	$url = new stdClass();
    	$url->prefix = "wy1";
    	$url->import_url = "http://www.health.wyo.gov/Media.aspx?mediaId=18045";
    	 
    	$mock->shouldReceive("getUrls")->andReturn(array($url));
    	
    	$blobImgData = file_get_contents($url->import_url);
    	
    	$mock->shouldReceive("getBlobOfFile")->andReturn($blobImgData);
    	
    	$mock->refreshRecords();
    }
    
    public function testRefreshRecordsPrefixExistsWillUpdate()
    {
    	$mock = $this->container->mock("App\Services\ImportFileService[getUrls, getBlobOfFile]");
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
    
    public function testRefreshRecordsNoPrefixWillInsert()
    {
    	$mock = $this->container->mock("App\Services\ImportFileService[getUrls, getBlobOfFile, isPrefixExists]");
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
    
    /**
     * Test for the getExclusionList method of ImportFileService.
     *
     * Asserts not null for the list retrieved.
     * Asserts if accr is equal to expected accr.
     */
    public function testGetExclusionList()
    {
    	$list = $this->importFileService->getExclusionList();
    	 
    	$this->assertNotNull($list);
    	 
    	// checks for AZ1
    	$this->assertEquals('AZ AHCCCS', $list["az1"]["accr"]);
    	 
    	// checks for NYOMIG
    	$this->assertEquals('NY OMIG', $list["nyomig"]["accr"]);
    	 
    	// IL1 should not be included
    	$this->assertFalse(array_key_exists('il1', $list));
    }
    
    /**
     * Test for the importFile method of ImportFileService using the existing import url.
     *
     */
    public function testImportFileForExistingImportUrl()
    {
    	// instantiate the ImportFileService
    	$this->importFileService = $this->container->mock("App\Services\ImportFileService[getListObject,getListProcessor,getUrl,isStateUpdateable]");
    	$this->importFileService = $this->importFileService->shouldAllowMockingProtectedMethods();
    
    	// mock getUrl method and return the Url
    	$this->importFileService->shouldReceive('getUrl')->once()->andReturn("http://www.omig.ny.gov/data/gensplistns.php");
    	 
    	// mock isStateUpdateable and return true
    	$this->importFileService->shouldReceive('isStateUpdateable')->once()->andReturn(true);
    	 
    	// mock exclusion list object to bypass retrieveData function
    	$exclusionList = $this->container->mock("App\Import\Lists\ExclusionLists");
    	$exclusionList->shouldReceive("retrieveData")->once();
    	$this->importFileService->shouldReceive("getListObject")->andReturn($exclusionList);
    
    	// mock list processor to bypass insertRecords function
    	$listProcessor = $this->container->mock("App\Import\Service\ListProcessor");
    	$listProcessor->shouldReceive("insertRecords")->once();
    	$this->importFileService->shouldReceive("getListProcessor")->andReturn($listProcessor);
    	 
    	$inputUrl = "";
    	$listPrefix = "nyomig";
    	 
    	$response = $this->importFileService->importFile($inputUrl, $listPrefix, false);
    	 
    	$this->assertNotNull($response);
    	$this->assertTrue($response->getData()->success);
    }
    
    /**
     * Test for the importFile method of ImportFileService using the new import url.
     *
     */
    public function testImportFileForNewImportUrl()
    {
    	// instantiate the ImportFileService
    	$this->importFileService = $this->container->mock("App\Services\ImportFileService[getListObject,getListProcessor]");
    	$this->importFileService = $this->importFileService->shouldAllowMockingProtectedMethods();
    	 
    	// mock exclusion list object to bypass retrieveData function
    	$exclusionList = $this->container->mock("App\Import\Lists\ExclusionLists");
    	$exclusionList->shouldReceive("retrieveData")->once();
    	$this->importFileService->shouldReceive("getListObject")->andReturn($exclusionList);
    	 
    	// mock list processor to bypass insertRecords function
    	$listProcessor = $this->container->mock("App\Import\Service\ListProcessor");
    	$listProcessor->shouldReceive("insertRecords")->once();
    	$this->importFileService->shouldReceive("getListProcessor")->andReturn($listProcessor);
    
    	$inputUrl = "http://www.omig.ny.gov/data/gensplistns.php";
    	$listPrefix = "nyomig";
    
    	$response = $this->importFileService->importFile($inputUrl, $listPrefix, true);
    
    	$this->assertNotNull($response);
    	$this->assertTrue($response->getData()->success);
    }
    
    /**
     * Test for the importFile method of ImportFileService using an invalid prefix value.
     *
     */
    public function testImportFileForInvalidPrefix()
    {
    	// instantiate the ImportFileService
    	$this->importFileService = new ImportFileService();
    
    	$inputUrl = "";
    	$listPrefix = "invalidPrefix";
    
    	$response = $this->importFileService->importFile($inputUrl, $listPrefix, false);
    	 
    	$this->assertNotNull($response);
    	$this->assertFalse($response->getData()->success);
    	$this->assertEquals("Unsupported Exclusion List prefix: invalidPrefix", $response->getData()->msg);
    }
    
    /**
     * Test for the importFile method of ImportFileService using an invalid prefix value.
     *
     */
    public function testImportFileWithStateUpdateableFalse()
    {
    	// mock service
    	$this->importFileService = $this->container->mock("App\Services\ImportFileService[getUrl, isStateUpdateable]");
    	$this->importFileService = $this->importFileService->shouldAllowMockingProtectedMethods();
    	 
    	// mock getUrl method and return the Url
    	$this->importFileService->shouldReceive('getUrl')->once()->andReturn("http://www.omig.ny.gov/data/gensplistns.php");
    	 
    	// mock isStateUpdateable and return true
    	$this->importFileService->shouldReceive('isStateUpdateable')->once()->andReturn(false);
    	 
    	$inputUrl = "";
    	$listPrefix = "nyomig";
    
    	$response = $this->importFileService->importFile($inputUrl, $listPrefix, false);
    
    	$this->assertNotNull($response);
    	$this->assertFalse($response->getData()->success);
    	$this->assertEquals("State is already up-to-date.", $response->getData()->msg);
    }
    
    /**
     * Test for the updateStateUrl method of FileService.
     * State prefix and new url are passed as parameters.
     *
     * Asserts that the number of updated records is one.
     * Asserts that the updated record is found in the database.
     */
    public function testUpdateStateUrl()
    {
    	$class = new ReflectionClass('App\Services\ImportFileService');
    	$method = $class->getMethod('updateStateUrl');
    	$method->setAccessible(true);
    	$method->invokeArgs(new ImportFileService(), array('az1', 'www.yahoo.com'));
    	 
    	$this->tester->seeInDatabase('exclusion_lists', array('prefix' => 'az1', 'import_url' => 'www.yahoo.com'));
    }
    
    /**
     * Test for the getUrl method of FileService.
     * State prefix is passed as parameter.
     *
     * Asserts not null for the url retrieved.
     * Asserts that url is equal to expected url.
     */
    public function testGetUrl()
    {
    	$class = new ReflectionClass('App\Services\ImportFileService');
    	$method = $class->getMethod('getUrl');
    	$method->setAccessible(true);
    	$url = $method->invokeArgs(new ImportFileService(), array('az1'));
    	 
    	$this->assertEquals('www.yahoo.com', $url);
    }
    
    /**
     * Test for the isStateUpdateable method of FileService.
     * State prefix is passed as parameter.
     *
     * Asserts that state is updateable.
     */
    public function testIsStateUpdateable()
    {
    	$class = new ReflectionClass('App\Services\ImportFileService');
    	$method = $class->getMethod('isStateUpdateable');
    	$method->setAccessible(true);
    	$result = $method->invokeArgs(new ImportFileService(), array('az1'));
    	 
    	$this->assertTrue($result);
    }
}
