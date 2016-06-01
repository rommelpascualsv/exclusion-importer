<?php
namespace App\Events;

class FileUpdateFailed extends FileImportEvent
{
    public function __construct($timestamp, $description, $objectId)
    {
        parent::__construct($timestamp, self::EVENTTYPE_FILE_UPDATE, $description, self::EVENTSTATUS_FAIL, $objectId);
    }
}
