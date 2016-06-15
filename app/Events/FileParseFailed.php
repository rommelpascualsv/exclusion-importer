<?php
namespace App\Events;

class FileParseFailed extends FileImportEvent
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_FILE_PARSE)
             ->setStatus(self::EVENTSTATUS_FAIL);
    }
}
