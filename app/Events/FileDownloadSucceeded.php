<?php
namespace App\Events;

class FileDownloadSucceeded extends FileImportEvent
{
    const DEFAULT_DESCRIPTION = 'File downloaded successfully';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_FILE_DOWNLOAD)
             ->setStatus(self::EVENTSTATUS_SUCCESS)
             ->setDescription(self::DEFAULT_DESCRIPTION);
    }
    
}
