<?php

namespace App\Import\CredentialLists;

use App\Import\CredentialLists\NJCredentialFileMaker;

class CredentialFileMakerFactory
{
    public function createCredentialFileMaker($prefix, $filePath)
    {
        if ($prefix === 'njcredential')
        {
            return new NJCredentialFileMaker($filePath);
        }
    }
}
