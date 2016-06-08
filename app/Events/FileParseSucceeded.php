<?php
namespace App\Events;

class FileParseSucceeded extends FileImportEvent
{
    const DEFAULT_DESCRIPTION = 'File parsed successfully';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_FILE_PARSE)
             ->setStatus(self::EVENTSTATUS_SUCCESS)
             ->setDescription(self::DEFAULT_DESCRIPTION);
    }
}
