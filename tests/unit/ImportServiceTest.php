<?php

use App\Services\ImportService;
use App\Url;

class ImportServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected $importService;
    
    protected $fileService;

    protected function _before()
    {
    	$this->container = new \Mockery\Container;
    	$this->importService = new ImportService();
    }

    protected function _after()
    {
    	$this->container->mockery_close();
    }

    /**
     * Test for the getExclusionList method of ImportService.
     * 
     * Asserts not null for the list retrieved.
     * Asserts if accr is equal to expected accr.
     */
    public function testGetExclusionList()
    {
    	$list = $this->importService->getExclusionList();
    	
    	$this->assertNotNull($list);
    	
    	// checks for AZ1
    	$this->assertEquals('AZ AHCCCS', $list["az1"]["accr"]);
    	
    	// checks for NYOMIG
    	$this->assertEquals('NY OMIG', $list["nyomig"]["accr"]);
    	
    	// IL1 should not be included
    	$this->assertFalse(array_key_exists('il1', $list));
    }
    
    /**
     * Test for the importFile method of ImportService using the existing import url.
     *
     */
    public function testImportFileForExistingImportUrl()
    {
    	// instantiate the ImportService
    	$this->importService = $this->container->mock("App\Services\ImportService[getListObject,getListProcessor,getUrl,isStateUpdateable]");
    	$this->importService = $this->importService->shouldAllowMockingProtectedMethods();
    	 
    	// new Url object for mock return
    	$url = new Url();
    	$url->url = "http://www.omig.ny.gov/data/gensplistns.php";
    	
    	// mock getUrl method and return the Url object
    	$this->importService->shouldReceive('getUrl')->once()->andReturn([$url]);
    	
    	// mock isStateUpdateable and return true
    	$this->importService->shouldReceive('isStateUpdateable')->once()->andReturn(true);
    	
    	// mock exclusion list object to bypass retrieveData function
    	$exclusionList = $this->container->mock("App\Import\Lists\ExclusionLists");
    	$exclusionList->shouldReceive("retrieveData")->once();
    	$this->importService->shouldReceive("getListObject")->andReturn($exclusionList);
    	 
    	// mock list processor to bypass insertRecords function
    	$listProcessor = $this->container->mock("App\Import\Service\ListProcessor");
    	$listProcessor->shouldReceive("insertRecords")->once();
    	$this->importService->shouldReceive("getListProcessor")->andReturn($listProcessor);
    	
    	$inputUrl = "";
    	$listPrefix = "nyomig";
    	
    	$response = $this->importService->importFile($inputUrl, $listPrefix);
    	
    	$this->assertNotNull($response);
    	$this->assertTrue($response->getData()->success);
    }
    
    /**
     * Test for the importFile method of ImportService using the new import url.
     *
     */
    public function testImportFileForNewImportUrl()
    {
    	// instantiate the ImportService
    	$this->importService = $this->container->mock("App\Services\ImportService[getListObject,getListProcessor]");
    	$this->importService = $this->importService->shouldAllowMockingProtectedMethods();
    	
    	// mock exclusion list object to bypass retrieveData function
    	$exclusionList = $this->container->mock("App\Import\Lists\ExclusionLists");
    	$exclusionList->shouldReceive("retrieveData")->once();
    	$this->importService->shouldReceive("getListObject")->andReturn($exclusionList);
    	
    	// mock list processor to bypass insertRecords function
    	$listProcessor = $this->container->mock("App\Import\Service\ListProcessor");
    	$listProcessor->shouldReceive("insertRecords")->once();
    	$this->importService->shouldReceive("getListProcessor")->andReturn($listProcessor);
    
    	$inputUrl = "http://www.omig.ny.gov/data/gensplistns.php";
    	$listPrefix = "nyomig";
    	 
    	$response = $this->importService->importFile($inputUrl, $listPrefix);
    	 
    	$this->assertNotNull($response);
    	$this->assertTrue($response->getData()->success);
    }
    
    /**
     * Test for the importFile method of ImportService using an invalid prefix value.
     *
     */
    public function testImportFileForInvalidPrefix()
    {
    	// new Url object for mock return
    	$url = new Url();
    	$url->url = "http://www.omig.ny.gov/data/gensplistns.php";
    
    	// instantiate the ImportService
    	$this->importService = new ImportService();
    
    	$inputUrl = "";
    	$listPrefix = "invalidPrefix";
    
    	$response = $this->importService->importFile($inputUrl, $listPrefix);
    	
    	$this->assertNotNull($response);
    	$this->assertFalse($response->getData()->success);
    	$this->assertEquals("Unsupported Exclusion List prefix: invalidPrefix", $response->getData()->msg);
    }
    
    /**
     * Test for the importFile method of ImportService using an invalid prefix value.
     *
     */
    public function testImportFileWithStateUpdateableFalse()
    {
    	// mock service
    	$this->importService = $this->container->mock("App\Services\ImportService[getUrl, isStateUpdateable]");
    	$this->importService = $this->importService->shouldAllowMockingProtectedMethods();
    	
    	// new Url object for mock return
    	$url = new Url();
    	$url->url = "http://www.omig.ny.gov/data/gensplistns.php";
    	
    	// mock getUrl method and return the Url object
    	$this->importService->shouldReceive('getUrl')->once()->andReturn([$url]);
    	
    	// mock isStateUpdateable and return true
    	$this->importService->shouldReceive('isStateUpdateable')->once()->andReturn(false);
    	
    	$inputUrl = "";
    	$listPrefix = "nyomig";
    
    	$response = $this->importService->importFile($inputUrl, $listPrefix);
    	 
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
    public function testUpdateStateUrl(){
    	$class = new ReflectionClass('App\Services\ImportService');
    	$method = $class->getMethod('updateStateUrl');
    	$method->setAccessible(true);
    	$method->invokeArgs(new ImportService(), array('az1', 'www.yahoo.com'));
    	
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
    	$class = new ReflectionClass('App\Services\ImportService');
    	$method = $class->getMethod('getUrl');
    	$method->setAccessible(true);
    	$url = $method->invokeArgs(new ImportService(), array('az1'));
    	
    	$this->assertEquals('www.yahoo.com', $url);
    }
    
    /**
     * Test for the isStateUpdateable method of FileService.
     * State prefix is passed as parameter.
     *
     * Asserts that state is updateable.
     */
    public function testIsStateUpdateable(){
    	$class = new ReflectionClass('App\Services\ImportService');
    	$method = $class->getMethod('isStateUpdateable');
    	$method->setAccessible(true);
    	$result = $method->invokeArgs(new ImportService(), array('az1'));
    	
    	$this->assertTrue($result);
    }
}