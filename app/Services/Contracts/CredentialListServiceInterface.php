<?php

namespace App\Services\Contracts;

interface CredentialListServiceInterface
{
    public function generateCredentialListFile($prefix, $destinationFilePath);
}
