<?php
namespace App\Events;

class FileUpdateFailed extends FileImportEvent
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_FILE_UPDATE)
             ->setStatus(self::EVENTSTATUS_FAIL);
    }
}
