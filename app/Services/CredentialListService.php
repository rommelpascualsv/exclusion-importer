<?php

namespace App\Services;

use App\Services\Contracts\CredentialListServiceInterface;

class CredentialListService implements CredentialListServiceInterface
{
    
    public function __construct()
    {
        
    }
    
    public function generateCredentialList($prefix, $destinationFilePath = null)
    {
        if (! $prefix) {
            throw new CredentialListServiceException('No credential list prefix specified');
        }
    }
}
