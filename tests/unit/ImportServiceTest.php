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

    protected function _before()
    {
    	$this->container = new \Mockery\Container;
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
    	// mock service
    	$service = $this->container->mock("App\Services\Contracts\FileServiceInterface");
    	
    	$this->importService = new ImportService($service);
    	
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
    	// mock service
    	$service = $this->container->mock("App\Services\Contracts\FileServiceInterface");
    	
    	// new Url object for mock return
    	$url = new Url();
    	$url->url = "http://www.omig.ny.gov/data/gensplistns.php";
    	
    	// mock getUrl method and return the Url object
    	$service->shouldReceive('getUrl')->once()->andReturn([$url]);
    	
    	// mock isStateUpdateable and return true
    	$service->shouldReceive('isStateUpdateable')->once()->andReturn(true);
    	
    	// instantiate the ImportService
    	$this->importService = $this->container->mock("App\Services\ImportService[getListObject,getListProcessor]", array($service));
    	$this->importService = $this->importService->shouldAllowMockingProtectedMethods();
    	 
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
    	// mock service
    	$service = $this->container->mock("App\Services\Contracts\FileServiceInterface");
    	
    	// mock updateStateUrl method
    	$service->shouldReceive('updateStateUrl')->once();
    	 
    	// mock isStateUpdateable and return true
    	$service->shouldReceive('isStateUpdateable')->never()->andReturn(true);
    	 
    	// instantiate the ImportService
    	$this->importService = $this->container->mock("App\Services\ImportService[getListObject,getListProcessor]", array($service));
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
    	// mock service
    	$service = $this->container->mock("App\Services\Contracts\FileServiceInterface");
    
    	// new Url object for mock return
    	$url = new Url();
    	$url->url = "http://www.omig.ny.gov/data/gensplistns.php";
    
    	// instantiate the ImportService
    	$this->importService = new ImportService($service);
    
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
    	$service = $this->container->mock("App\Services\Contracts\FileServiceInterface");
    
    	// new Url object for mock return
    	$url = new Url();
    	$url->url = "http://www.omig.ny.gov/data/gensplistns.php";
    	
    	// mock getUrl method and return the Url object
    	$service->shouldReceive('getUrl')->once()->andReturn([$url]);
    	
    	// mock isStateUpdateable and return true
    	$service->shouldReceive('isStateUpdateable')->once()->andReturn(false);
    	
    	// instantiate the ImportService
    	$this->importService = new ImportService($service);
    
    	$inputUrl = "";
    	$listPrefix = "nyomig";
    
    	$response = $this->importService->importFile($inputUrl, $listPrefix);
    	 
    	$this->assertNotNull($response);
    	$this->assertFalse($response->getData()->success);
    	$this->assertEquals("State is already up-to-date.", $response->getData()->msg);
    }
}