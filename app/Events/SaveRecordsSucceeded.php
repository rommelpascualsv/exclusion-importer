<?php
namespace App\Events;

class SaveRecordsSucceeded extends FileImportEvent
{
    const DEFAULT_DESCRIPTION = 'Records saved successfully';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_SAVE_RECORDS)
             ->setStatus(self::EVENTSTATUS_SUCCESS)
             ->setDescription(self::DEFAULT_DESCRIPTION);
    }
}
