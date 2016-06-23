<?php

namespace App\Import\CredentialLists;

abstract class CredentialFileMaker
{
    protected $filePath;
    
    public function construct($filePath) {
        $this->$filePath = $filePath;
    }
    
    protected abstract function buildFile();
    
}
