<?php

namespace App\Console\Commands\NJCredential;

use App\Console\Commands\ImportCredentialsCommandTemplate;

class Import extends ImportCredentialsCommandTemplate
{
    protected $name = 'njcredential:import';

    protected $description = 'Import the NJ Credential Database to a storage location';

    protected function getPrefix()
    {
        return 'njcredential';
    }
}
