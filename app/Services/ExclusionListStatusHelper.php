<?php
namespace App\Services;

use App\Events\Event;
use App\Events\FileImportEventFactory;
use App\Repositories\EventRepository;
use App\Repositories\ExclusionListRecordRepository;
use App\Repositories\ExclusionListRepository;
use App\Repositories\FileRepository;

class ExclusionListStatusHelper
{
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListRecordRepo;
    private $fileImportEventRepo;
    
    public function __construct(ExclusionListRepository $exclusionListRepo,
                                FileRepository $exclusionListFileRepo,
                                ExclusionListRecordRepository $exclusionListRecordRepo,
                                EventRepository $fileImportEventRepo)
    {
        $this->exclusionListRepo = $exclusionListRepo;
        $this->exclusionListFileRepo = $exclusionListFileRepo;
        $this->exclusionListRecordRepo = $exclusionListRecordRepo;
        $this->fileImportEventRepo = $fileImportEventRepo;
    }
    
    public function isUpdateRequired($prefix, $hash)
    {
        return ! $this->isLastImportedHashEqualTo($hash, $prefix)
            || ! $this->isLastEventSuccessful($prefix)
            || $this->isExclusionListRecordsEmpty($prefix);
    }
    
    private function isLastImportedHashEqualTo($hash, $prefix)
    {
        $records = $this->exclusionListRepo->find($prefix);
    
        return $records && $records[0]->last_imported_hash === $hash;
    }
    
    private function isLastEventSuccessful($prefix)
    {
        $event = $this->fileImportEventRepo->findLatestEventOfObjectId($prefix, FileImportEventFactory::EVENT_TYPES);
         
        return $event && $event->getStatus() === Event::EVENTSTATUS_SUCCESS;
    }
    
    private function isExclusionListRecordsEmpty($prefix)
    {
        return $this->exclusionListRecordRepo->size($prefix) === 0;
    }    
}