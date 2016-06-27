<?php

namespace Test\Unit;

use CDM\Test\TestCase;
use App\Services\ExclusionListStatusHelper;
use App\Repositories\FileRepository;
use App\Repositories\ExclusionListRepository;
use App\Repositories\EventRepository;
use Mockery;
use App\Events\FileImportEventFactory;

class ExclusionListStatusHelperTest extends TestCase
{
    
    private $helper;
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListRecordRepo;
    private $fileImportEventRepo;
    
    public function setUp()
    {
        parent::setUp();
    
        $this->app->withFacades();
    
        $this->exclusionListRepo = Mockery::mock('App\Repositories\ExclusionListRepository')->makePartial();
        $this->exclusionListFileRepo = Mockery::mock('App\Repositories\FileRepository');
        $this->exclusionListRecordRepo = Mockery::mock('App\Repositories\ExclusionListRecordRepository')->makePartial();
        $this->fileImportEventRepo = Mockery::mock('App\Repositories\EventRepository')->makePartial();
        
        $this->helper = new ExclusionListStatusHelper(
            $this->exclusionListRepo,
            $this->exclusionListFileRepo,
            $this->exclusionListRecordRepo,
            $this->fileImportEventRepo
        );
    }
    
    public function testIsUpdateRequiredShouldReturnTrueIfLastImportedHashIsNotEqualToPassedHash()
    {
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'prefix' => 'tn1',
            'last_imported_hash' => 'latest_hash'
        ]]);
        
        $this->assertTrue($this->helper->isUpdateRequired('tn1', 'hash_not_matching_the_latest_hash'));
        
    }
    
    public function testIsUpdateRequiredShouldReturnTrueIfRecordsTableIsEmpty()
    {
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'prefix' => 'tn1',
            'last_imported_hash' => 'latest_hash'
        ]]);
        
        $this->exclusionListRecordRepo->shouldReceive('size')->with('tn1')->andReturn(0);
        
        $this->assertTrue($this->helper->isUpdateRequired('tn1', 'latest_hash'));
    }  
    
    public function testIsUpdateRequiredShouldReturnTrueIfLastEventForPrefixFailed()
    {
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'prefix' => 'tn1',
            'last_imported_hash' => 'latest_hash'
        ]]);
        
        $this->exclusionListRecordRepo->shouldReceive('size')->with('tn1')->andReturn(100);
        
        $this->fileImportEventRepo->shouldReceive('findLatestEventOfObjectId')->once()->with('tn1', FileImportEventFactory::EVENT_TYPES)->andReturn(FileImportEventFactory::newFileUpdateFailed());
        
        $this->assertTrue($this->helper->isUpdateRequired('tn1', 'latest_hash'));
    }
    
    public function testIsUpdateRequiredShouldReturnFalseIfLastImportedHashEqualsPassedHashAndRecordsTableIsNotEmptyAndLastEventForPrefixSucceeded()
    {
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'prefix' => 'tn1',
            'last_imported_hash' => 'latest_hash'
        ]]);
    
        $this->exclusionListRecordRepo->shouldReceive('size')->with('tn1')->andReturn(100);
    
        $this->fileImportEventRepo->shouldReceive('findLatestEventOfObjectId')->once()->with('tn1', FileImportEventFactory::EVENT_TYPES)->andReturn(FileImportEventFactory::newSaveRecordsSucceeded());
    
        $this->assertFalse($this->helper->isUpdateRequired('tn1', 'latest_hash'));
    }    
}
