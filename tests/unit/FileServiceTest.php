<?php

use App\Services\FileService;

class FileServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
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
}