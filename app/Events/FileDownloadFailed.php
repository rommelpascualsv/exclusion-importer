<?php
namespace App\Events;

class FileDownloadFailed extends FileImportEvent
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_FILE_DOWNLOAD)
             ->setStatus(self::EVENTSTATUS_FAIL);
    }
}
