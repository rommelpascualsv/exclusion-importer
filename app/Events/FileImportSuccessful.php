<?php
namespace App\Events;

class FileImportSuccessful extends FileImportEvent
{
    public function __construct($timestamp, $description, $objectId)
    {
        parent::__construct($timestamp, self::EVENTTYPE_FILE_IMPORT, $description, self::EVENTSTATUS_SUCCESS, $objectId);
    }
}
