<?php

namespace App\Console\Commands\NJCredential;

use App\Import\CredentialLists\NJCredentialFileMaker;
use App\Repositories\CredentialDatabaseRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Import extends Command
{
    protected $name = 'njcredential:import';

    protected $description = 'Import the NJ Credential Database to a storage location';

    public function fire()
    {
        $destinationFilePath = storage_path() . $this->getDestinationFilePath();

        if (! $destinationFilePath) {
            throw new \RuntimeException('missing destination file path. Set one in the credential_databases table or pass one in as an argument.');
        }

        $reScrapeFilePath = $destinationFilePath . '.rescrape';

        $client = new NJCredentialFileMaker($destinationFilePath, $reScrapeFilePath);
        $client->buildFile();
    }

    protected function getDestinationFilePath()
    {
        if ($path = $this->argument('destFilePath')) {
            return $path;
        }

        $credentialDatabaseRecord = (new CredentialDatabaseRepository())->find('njcredential');
        if (! $credentialDatabaseRecord) {
            return null;
        }

        return $credentialDatabaseRecord->storage_path;
    }

    public function getArguments()
    {
        return [
            ['destFilePath', InputArgument::OPTIONAL, 'The destination file path for the imported list']
        ];
    }
}
