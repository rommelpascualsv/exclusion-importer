<?php

namespace App\Import\CredentialLists;

class CredentialFileMakerFactory
{
    public function createCredentialFileMaker($prefix, $sourceUri)
    {
        if ('njcredential' === $prefix) {
            return new NJCredentialFileMaker($sourceUri);

        } else if ('nppes' === $prefix) {
            return new NppesCredentialFileMaker($sourceUri);

        } else if ('njna' === $prefix) {
            return new NjnaCredentialFileMaker($sourceUri);
        }

        throw new \RuntimeException('No appropriate credential file maker found for ' . $prefix);
    }
}
