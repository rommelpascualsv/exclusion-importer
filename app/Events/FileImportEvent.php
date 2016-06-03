<?php 
namespace App\Events;

use App\Events\Event;

abstract class FileImportEvent extends Event
{
    const EVENTTYPE_FILE_DOWNLOAD = 'file.download';
    const EVENTTYPE_FILE_UPDATE   = 'file.update';
    const EVENTTYPE_FILE_PARSE    = 'file.parse';
    const EVENTTYPE_SAVE_RECORDS  = 'records.save'; 
    
    const EVENTSTATUS_SUCCESS = '1';
    const EVENTSTATUS_FAIL    = '0';
    
    const TS_FORMAT = 'Y-m-d H:i:s';
    
    private $timestamp;
    private $eventType;
    private $description;
    private $status;
    private $objectId;
    
    public function __construct()
    {
        $this->setTimestamp(date(self::TS_FORMAT));
    }
    
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }
    
    public function getEventType()
    {
        return $this->eventType;
    }
    
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
        return $this;
    }
}
