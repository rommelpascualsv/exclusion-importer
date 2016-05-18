<?php
namespace Import\Scrape\ProgressLogers;

use App\Import\Scrape\ProgressTrackers\ProgressTrackerInterface;

class LogsProgressTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->trait = $this->getMockForTrait('App\Import\Scrape\ProgressTrackers\TracksProgress');
    }

    protected function _after()
    {
    }

    // tests
    public function testAttachProgressTracker()
    {
        $progressTrackerMock = $this->getMock(ProgressTrackerInterface::class); 
        
        $self = $this->trait->attachProgressTracker($progressTrackerMock);
        
        $progressTrackers = $this->trait->getProgressTrackers();
        
        $this->assertSame($progressTrackerMock, $progressTrackers[key($progressTrackers)]);
        
        // assert self
        $this->assertSame($this->trait, $self);
    }
    
    public function testDetachProgressTracker()
    {
        $progressTrackerMock = $this->getMock(ProgressTrackerInterface::class);
        
        $this->trait->attachProgressTracker($progressTrackerMock);
        
        $self = $this->trait->detachProgressTracker($progressTrackerMock);
        
        $this->assertCount(0, $this->trait->getProgressTrackers());
        
        // assert self
        $this->assertSame($this->trait, $self);
    }
    
    /**
     * Test track progress
     */
    public function testTrackProgress()
    {
        $progressTrackerMock = $this->getMock(ProgressTrackerInterface::class);
        
        $this->trait->attachProgressTracker($progressTrackerMock);
        
        // assert that ProgressTrackerInterface::line is called with the correct params when running trackProgress
        $progressTrackerMock->expects($this->once())
            ->method('notice')
            ->with('Some line', []);
        
        $this->trait->trackProgress('Some line');        
    }
    
    /**
     * Test track info progress
     */
    public function testTrackInfoProgress()
    {
        $progressTrackerMock = $this->getMock(ProgressTrackerInterface::class);
    
        $this->trait->attachProgressTracker($progressTrackerMock);
    
        // assert that ProgressTrackerInterface::info is called with the correct params when running trackInfoProgress
        $progressTrackerMock->expects($this->once())
            ->method('info')
            ->with('Some info line', []);
    
        $this->trait->trackInfoProgress('Some info line');
    }
    
    /**
     * Test track info progress
     */
    public function testTrackErrorProgress()
    {
        $progressTrackerMock = $this->getMock(ProgressTrackerInterface::class);
    
        $this->trait->attachProgressTracker($progressTrackerMock);
    
        // assert that ProgressTrackerInterface::error is called with the correct params when running trackErrorProgress
        $progressTrackerMock->expects($this->once())
            ->method('error')
            ->with('Some error line', []);
    
        $this->trait->trackErrorProgress('Some error line');
    }
}