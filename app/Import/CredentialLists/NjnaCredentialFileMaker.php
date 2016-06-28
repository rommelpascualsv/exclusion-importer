<?php

namespace App\Import\CredentialLists;

use GuzzleHttp\Client;

class NjnaCredentialFileMaker extends CredentialFileMaker
{

    const CONNECT_TIMEOUT = 10;

    public function buildFile($destinationFilePath)
    {
        try
        {
            info('Downloading credential list file for Njna from ' . $this->sourceUri . ' to ' . $destinationFilePath);

            $this->downloadFileTo($destinationFilePath);

        } catch (\Exception $e) {

            error('Unable to download credential list file for Njna from ' . $this->sourceUri . ' : ' . $e->getMessage());
            throw $e;
        }
    }

    private function downloadFileTo($destinationFilePath)
    {
        $client = new Client();

        $client->get($this->sourceUri, [
            'sink' => $destinationFilePath,
            'connect_timeout' => self::CONNECT_TIMEOUT,
            'verify' => false
        ]);
    }

}