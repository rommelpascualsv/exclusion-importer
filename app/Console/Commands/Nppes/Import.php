<?php

namespace App\Console\Commands\Nppes;

use App\Console\Commands\BaseImportCredentials;

class Import extends BaseImportCredentials
{
    protected $name = 'nppes:import';

    protected $description = 'Import the NPPES Credential Database to a storage location';

    protected function getPrefix()
    {
        return 'nppes';
    }
}
