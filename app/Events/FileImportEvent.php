<?php 
namespace App\Events;

use App\Events\Event;

class FileImportEvent extends Event
{
    const EVENTTYPE_FILE_UPLOAD   = 'file.upload';
    const EVENTTYPE_FILE_DOWNLOAD = 'file.download';
    const EVENTTYPE_FILE_UPDATE   = 'file.update';
    const EVENTTYPE_FILE_PARSE    = 'file.parse';
    const EVENTTYPE_SAVE_RECORDS  = 'records.save'; 
    
    const EVENT_TYPES = [
        self::EVENTTYPE_FILE_UPLOAD,
        self::EVENTTYPE_FILE_DOWNLOAD, 
        self::EVENTTYPE_FILE_UPDATE,
        self::EVENTTYPE_FILE_PARSE,
        self::EVENTTYPE_SAVE_RECORDS
    ];
    
    const EVENTSTATUS_SUCCESS = '1';
    const EVENTSTATUS_FAIL    = '0';
    
    const DEFAULT_FILE_UPLOAD_SUCCESS_DESCRIPTION = 'File uploaded successfully';
    const DEFAULT_FILE_DOWNLOAD_SUCCESS_DESCRIPTION = 'File downloaded successfully';
    const DEFAULT_FILE_PARSE_SUCCESS_DESCRIPTION    = 'File parsed successfully';
    const DEFAULT_FILE_UPDATE_SUCCESS_DESCRIPTION   = 'File updated successfully';
    const DEFAULT_SAVE_RECORDS_SUCCESS_DESCRIPTION  = 'Records saved successfully';
    
    const TS_FORMAT = 'Y-m-d H:i:s';
    
    private $timestamp;
    private $eventType;
    private $description;
    private $status;
    private $objectId;
    
    public static function newFileDownloadFailed()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_FILE_DOWNLOAD)
            ->setStatus(self::EVENTSTATUS_FAIL);
    
        return $instance;
    }
    
    public static function newFileDownloadSucceeded()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_FILE_DOWNLOAD)
            ->setStatus(self::EVENTSTATUS_SUCCESS)
            ->setDescription(json_encode(['message' => self::DEFAULT_FILE_DOWNLOAD_SUCCESS_DESCRIPTION]));
    
        return $instance;
    }
    
    public static function newFileParseFailed()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_FILE_PARSE)
            ->setStatus(self::EVENTSTATUS_FAIL);
    
        return $instance;
    }
    
    public static function newFileParseSucceeded()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_FILE_PARSE)
            ->setStatus(self::EVENTSTATUS_SUCCESS)
            ->setDescription(json_encode(['message' => self::DEFAULT_FILE_PARSE_SUCCESS_DESCRIPTION]));
            
        return $instance;
    }
    
    public static function newFileUpdateFailed()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_FILE_UPDATE)
            ->setStatus(self::EVENTSTATUS_FAIL);
        
        return $instance;
    }
    
    public static function newFileUpdateSucceeded()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_FILE_UPDATE)
            ->setStatus(self::EVENTSTATUS_SUCCESS)
            ->setDescription(json_encode(['message' => self::DEFAULT_FILE_UPDATE_SUCCESS_DESCRIPTION]));
        
        return $instance;
    }
    
    public static function newFileUploadFailed()
    {
        $instance = (new FileImportEvent())
        ->setEventType(self::EVENTTYPE_FILE_UPLOAD)
        ->setStatus(self::EVENTSTATUS_FAIL);
    
        return $instance;
    }
    
    public static function newFileUploadSucceeded()
    {
        $instance = (new FileImportEvent())
        ->setEventType(self::EVENTTYPE_FILE_UPLOAD)
        ->setStatus(self::EVENTSTATUS_SUCCESS)
        ->setDescription(json_encode(['message' => self::DEFAULT_FILE_UPLOAD_SUCCESS_DESCRIPTION]));
    
        return $instance;
    }    
    
    public static function newSaveRecordsFailed()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_SAVE_RECORDS)
            ->setStatus(self::EVENTSTATUS_FAIL);
        
        return $instance;
    }    

    public static function newSaveRecordsSucceeded()
    {
        $instance = (new FileImportEvent())
            ->setEventType(self::EVENTTYPE_SAVE_RECORDS)
            ->setStatus(self::EVENTSTATUS_SUCCESS)
            ->setDescription(json_encode(['message' => self::DEFAULT_SAVE_RECORDS_SUCCESS_DESCRIPTION]));
        
        return $instance;
    }
    
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
