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
    
    /**
     * Instantiate the FileService.
     */
    protected function _before()
    {
    	$this->fileService = new FileService();
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
     * Test for the getUrl method of FileService.
     * State prefix is passed as parameter.
     * 
     * Asserts not null for the url retrieved.
     * Asserts that url is equal to expected url.
     */
    public function testGetUrl(){
    	$url = $this->fileService->getUrl('az1');
    	//$this->assertNotNull($url);
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
     * Test for the updateStateUrl method of FileService.
     * State prefix and new url are passed as parameters.
     * 
     * Asserts that the number of updated records is one.
     * Asserts that the updated record is found in the database.
     */
    public function testUpdateStateUrl(){
    	$result = $this->fileService->updateStateUrl('az1', 'www.yahooo.com');
    	$this->assertEquals(1, $result);
    	$this->tester->seeInDatabase('exclusion_lists', array('prefix' => 'az1', 'import_url' => 'www.yahooo.com'));
    }
    
    public function testRefreshRecords(){
    	$result = $this->fileService->refreshRecords();
    }
}