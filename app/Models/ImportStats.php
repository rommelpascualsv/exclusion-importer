<?php

namespace App\Models;

class ImportStats implements \JsonSerializable
{
    private $added = 0;
    private $deleted = 0;
    private $previousRecordCount;
    private $currentRecordCount;
    
    public function getAdded()
    {
        return $this->added;
    }
    
    public function setAdded($added)
    {
        $this->added = $added;
        return $this;
    }
    
    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }
    
    public function getPreviousRecordCount()
    {
        return $this->previousRecordCount;
    }
    
    public function setPreviousRecordCount($previousRecordCount)
    {
        $this->previousRecordCount = $previousRecordCount;
        return $this;
    }
    
    public function getCurrentRecordCount()
    {
        return $this->currentRecordCount;
    }
    
    public function setCurrentRecordCount($currentRecordCount)
    {
        $this->currentRecordCount = $currentRecordCount;
        return $this;
    }
    
    public function getBrokenHashesPercentage()
    {
        if ($this->previousRecordCount === 0) {
            return 0;
        }
        
        return round(($this->added / $this->previousRecordCount) * 100, 1);
    }
    
    public function jsonSerialize()
    {
        return [
            'added' => $this->added,
            'deleted' => $this->deleted,
            'brokenHashPct' => $this->getBrokenHashesPercentage(),
            'previousRecordCount' => $this->previousRecordCount, 
            'currentRecordCount' => $this->currentRecordCount 
        ];
    }
}