<?php
namespace App\Events;

class FileUpdateSucceeded extends FileImportEvent
{
    const DEFAULT_DESCRIPTION = 'File updated successfully';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_FILE_UPDATE)
             ->setStatus(self::EVENTSTATUS_SUCCESS)
             ->setDescription(self::DEFAULT_DESCRIPTION);
    }
}
