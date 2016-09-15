<?php
namespace App\Services;

use App\Repositories\ExclusionListFileRepository;
use App\Repositories\ExclusionListRecordRepository;
use App\Repositories\FileImportEventRepository;
use App\Events\FileImportEvent;
use App\Repositories\ExclusionListRepository;

class ExclusionListStatusHelper
{
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListRecordRepo;
    private $fileImportEventRepo;
    
    public function __construct(ExclusionListRepository $exclusionListRepo,
        ExclusionListFileRepository $exclusionListFileRepo,
        ExclusionListRecordRepository $exclusionListRecordRepo,
        FileImportEventRepository $fileImportEventRepo)
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
        $event = $this->fileImportEventRepo->findLatestEventOfPrefix($prefix);
         
        return $event && $event->getStatus() === FileImportEvent::EVENTSTATUS_SUCCESS;
    }
    
    private function isExclusionListRecordsEmpty($prefix)
    {
        return $this->exclusionListRecordRepo->size($prefix) === 0;
    }    
}