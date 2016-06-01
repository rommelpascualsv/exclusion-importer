<?php 
namespace App\Events;

use App\Events\Event;

class FileImportEvent extends Event
{
    const EVENTTYPE_FILE_IMPORT     = 'I';
    const EVENTTYPE_FILE_UPDATE     = 'U';
    
    const EVENTSTATUS_SUCCESS       = 'S';
    const EVENTSTATUS_FAIL          = 'F';
    
    private $timestamp;
    private $eventType;
    private $description;
    private $status;
    private $objectId;
    
    public function __construct($timestamp, $eventType, $description, $status, $objectId)
    {
        $this->timestamp = $timestamp;
        $this->eventType = $eventType;
        $this->description = $description;
        $this->status = $status;
        $this->objectId = $objectId;
    }
    
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    public function getEventType()
    {
        return $this->eventType;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getStatus()
    {
        return $this->status;
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
}
