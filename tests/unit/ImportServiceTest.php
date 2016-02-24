<?php


use App\Services\ImportService;
use App\Services\FileService;
use Illuminate\Http\Request;
class ImportServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected $importService;

    protected function _before()
    {
    	$this->importService = new ImportService(new FileService());
    }

    protected function _after()
    {
    }


    /**
     * Test for the getExclusionList method of ImportService.
     * 
     * Asserts not null for the list retrieved.
     * Asserts if accr is equal to expected accr.
     */
//     public function testGetExclusionList()
//     {
//     	$list = $this->importService->getExclusionList();
    	
//     	$this->assertNotNull($list);
    	
//     	// checks for AZ1
//     	$this->assertEquals('AZ AHCCCS', $list["az1"]["accr"]);
    	
//     	// checks for NYOMIG
//     	$this->assertEquals('NY OMIG', $list["nyomig"]["accr"]);
    	
//     	// IL1 should not be included
//     	$this->assertFalse(array_key_exists('il1', $list));
//     }
    
    /**
     * Test for the importFile method of ImportService.
     *
     */
    public function testImportFile()
    {
    	
    	$request = new Request();
    	$request->input('url') = "http://www.omig.ny.gov/data/gensplistns.php";
    	
    	$listPrefix = "nyomig";
    	
    	$response = $this->importService->importFile($request, $listPrefix);
    	 
    	$this->assertNotNull($response);
    	
    }
}