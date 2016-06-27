<?php

namespace App\Import\CredentialLists;

class CredentialFileMakerFactory
{
    public function createCredentialFileMaker($prefix, $sourceUri)
    {
        if ($prefix === 'njcredential') {
            return new NJCredentialFileMaker($sourceUri);

        } else if ($prefix === 'nppes') {
            return new NppesCredentialFileMaker($sourceUri);
        }

        throw new \RuntimeException('No appropriate credential file maker found for ' . $prefix);
    }
}
