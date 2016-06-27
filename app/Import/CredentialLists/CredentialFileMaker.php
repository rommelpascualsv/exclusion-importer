<?php

namespace App\Import\CredentialLists;

abstract class CredentialFileMaker
{
    protected $sourceUri;
    
    public function __construct($sourceUri)
    {
        $this->sourceUri = $sourceUri;
    }

    public abstract function buildFile($destinationFilePath);

    public abstract function getFileType();
}
