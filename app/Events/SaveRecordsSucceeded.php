<?php
namespace App\Events;

class SaveRecordsSucceeded extends FileImportEvent implements \JsonSerializable
{
    const DEFAULT_DESCRIPTION = 'Records saved successfully';
    
    private $lastImportedHash; 
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEventType(self::EVENTTYPE_SAVE_RECORDS)
             ->setStatus(self::EVENTSTATUS_SUCCESS)
             ->setDescription(self::DEFAULT_DESCRIPTION);
    }
    
    public function getLastImportedHash()
    {
        return $this->lastImportedHash;
    }
    
    public function setLastImportedHash($lastImportedHash)
    {
        $this->lastImportedHash = $lastImportedHash;
        return $this;
    }
    
    public function jsonSerialize()
    {
        return [
            'lastImportedHash' => $this->lastImportedHash     
        ];
    }
    
}
