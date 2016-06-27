<?php 
namespace App\Events;

class FileImportEventFactory
{
    const EVENTTYPE_FILE_DOWNLOAD = 'file.download';
    const EVENTTYPE_FILE_UPDATE   = 'file.update';
    const EVENTTYPE_FILE_PARSE    = 'file.parse';
    const EVENTTYPE_SAVE_RECORDS  = 'records.save'; 
    
    const EVENT_TYPES = [
        self::EVENTTYPE_FILE_DOWNLOAD, 
        self::EVENTTYPE_FILE_UPDATE,
        self::EVENTTYPE_FILE_PARSE,
        self::EVENTTYPE_SAVE_RECORDS
    ];
    
    const DEFAULT_FILE_DOWNLOAD_SUCCESS_DESCRIPTION = 'File downloaded successfully';
    const DEFAULT_FILE_PARSE_SUCCESS_DESCRIPTION    = 'File parsed successfully';
    const DEFAULT_FILE_UPDATE_SUCCESS_DESCRIPTION   = 'File updated successfully';
    const DEFAULT_SAVE_RECORDS_SUCCESS_DESCRIPTION  = 'Records saved successfully';

    public static function newFileDownloadFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_DOWNLOAD)
                 ->setStatus(Event::EVENTSTATUS_FAIL);
    
        return $instance;
    }
    
    public static function newFileDownloadSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_DOWNLOAD)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_FILE_DOWNLOAD_SUCCESS_DESCRIPTION]));
    
        return $instance;
    }
    
    public static function newFileParseFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_PARSE)
                 ->setStatus(Event::EVENTSTATUS_FAIL);
    
        return $instance;
    }
    
    public static function newFileParseSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_PARSE)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_FILE_PARSE_SUCCESS_DESCRIPTION]));
            
        return $instance;
    }
    
    public static function newFileUpdateFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_UPDATE)
                 ->setStatus(Event::EVENTSTATUS_FAIL);
        
        return $instance;
    }
    
    public static function newFileUpdateSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_UPDATE)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_FILE_UPDATE_SUCCESS_DESCRIPTION]));
        
        return $instance;
    }
    
    public static function newSaveRecordsFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_SAVE_RECORDS)
                 ->setStatus(Event::EVENTSTATUS_FAIL);
        
        return $instance;
    }    

    public static function newSaveRecordsSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_SAVE_RECORDS)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_SAVE_RECORDS_SUCCESS_DESCRIPTION]));
        
        return $instance;
    }
}


