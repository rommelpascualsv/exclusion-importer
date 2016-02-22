<?php

use App\Services\FileService;

class FileServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
	protected $tester;
	
    protected $fileService;
    
    protected function _before()
    {
    	$this->fileService = new FileService();
    }

    protected function _after()
    {
    }

    public function testGetFile(){
    	$file = $this->fileService->getFile('ak1');
    	$this->assertNotNull($file);
    	$this->assertEquals('test.csv', $file[0]->file_name);
    }
    
    public function testGetUrl(){
    	$url = $this->fileService->getUrl('az1');
    	$this->assertNotNull($url);
    	$this->assertEquals('www.google.com', $url[0]->url);
    }
    
    public function testIsStateUpdateable(){
    	$result = $this->fileService->isStateUpdateable('az1');
    	$this->assertTrue($result);
    }
    
    public function testUpdateStateUrl(){
    	$result = $this->fileService->updateStateUrl('az1', 'www.yahoo.com');
    	$this->assertEquals(1, $result);
    	$this->tester->seeInDatabase('urls', array('state_prefix' => 'az1', 'url' => 'www.yahoo.com'));
    }
}