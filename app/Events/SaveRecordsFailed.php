<?php
namespace App\Events;

class SaveRecordsFailed extends FileImportEvent
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_SAVE_RECORDS)
             ->setStatus(self::EVENTSTATUS_FAIL);
    }
}
