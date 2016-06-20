<?php

namespace Test\Unit;

use App\Services\FileUploadException;
use App\Services\FileUploadService;
use CDM\Test\TestCase;
use Mockery;

/**
 * Unit test for FileUploadService. 
 * 
 * To run, execute the following in the shell : vendor/bin/codecept run unit FileUploadServiceTest --debug
 *
 */
class FileUploadServiceTest extends TestCase
{

    private $service;
    private $fileUploadRepo;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->fileUploadRepo = Mockery::mock('App\Repositories\FileUploadRepository')->makePartial();
        
        $this->service = new FileUploadService($this->fileUploadRepo);
        
        $this->withoutEvents();
    }

    /**
     * @expectedException App\Services\FileUploadException
     */    
    public function testUploadFileShouldThrowFileUploadExceptionIfFileAlreadyExistsInTheRepository()
    {
        
        $file = new \SplFileInfo(base_path('tests/unit/files/tn1-0.pdf'));
        
        $this->fileUploadRepo->shouldReceive('contains')->once()->with('tn1' . DIRECTORY_SEPARATOR . 'tn1-0.pdf')->andReturnTrue();
        
        $this->service->uploadFile($file, 'tn1');
        
    }
    
    public function testUploadFileShouldSaveFileWithExclusionListPrefixAsFilePathPrefixAndReturnUrlOfFile()
    {
    
        $fileToUpload = base_path('tests/unit/files/tn1-0.pdf');
        
        $file = new \SplFileInfo($fileToUpload);
    
        $this->fileUploadRepo->shouldReceive('contains')->once()->with('tn1' . DIRECTORY_SEPARATOR . 'tn1-0.pdf')->andReturnFalse();
        
        $this->fileUploadRepo->shouldReceive('create')->once()->with([
            'path' => 'tn1/tn1-0.pdf',
            'contents' => file_get_contents($fileToUpload)
        ])->andReturn('http://mycloudstorage.test/tn1/tn1-0.pdf');
        
        $actual = $this->service->uploadFile($file, 'tn1');
    
        $expected = 'http://mycloudstorage.test/tn1/tn1-0.pdf';
        
        $this->assertEquals($expected, $actual);
    }    
}
