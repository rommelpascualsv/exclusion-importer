<?php

namespace App\Services\Contracts;

interface CredentialListServiceInterface
{
    public function createCredentialDatabaseFile($prefix, $sourceUri, $destinationFile);
    
    public function seed($prefix, $credentialFile);
}
