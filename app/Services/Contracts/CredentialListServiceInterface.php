<?php

namespace App\Services\Contracts;

interface CredentialListServiceInterface
{
    public function generateCredentialList($prefix, $destinationFilePath);
}
