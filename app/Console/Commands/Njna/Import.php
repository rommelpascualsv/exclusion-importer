<?php

namespace App\Console\Commands\Njna;

use App\Console\Commands\BaseImportCredentials;

class Import extends BaseImportCredentials
{
    protected $name = 'njna:import';

    protected $description = 'Import the NJNA Credential Database to a storage location';

    protected function getPrefix()
    {
        return 'njna';
    }
}
