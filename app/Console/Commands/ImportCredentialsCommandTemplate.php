<?php

namespace App\Console\Commands;

use App\Repositories\CredentialDatabaseRepository;
use App\Services\CredentialListService;
use Illuminate\Console\Command;

abstract class ImportCredentialsCommandTemplate extends Command
{

    private $credentialListService;
    private $credentialDBRepo;

    public function __construct(CredentialListService $credentialListService,
                                CredentialDatabaseRepository $credentialDBRepo)
    {
        parent::__construct();

        ini_set('memory_limit', '2048M');

        $this->credentialListService = $credentialListService;
        $this->credentialDBRepo = $credentialDBRepo;
    }

    public function fire()
    {
        $destinationFile = $this->createDestinationFile();

        try {

            $prefix = $this->getPrefix();

            $credentialDatabase = $this->getCredentialDatabaseFor($prefix);

            if (! $credentialDatabase) {
                throw new \RuntimeException('No credential database defined for ' . $prefix);
            }

            $hash = $this->credentialListService->createCredentialDatabaseFile($prefix, $credentialDatabase->getSourceUri(), $destinationFile);
            
            if (! $credentialDatabase->getAutoSeed()) {
                info('Credential database for ' . $prefix . ' is not configured for auto-seeding. The process will now exit.');
                return;
            }

            if ($hash === $credentialDatabase->getLastImportHash()) {
                info('Last imported hash for ' . $prefix . ' is the same as generated credential file hash. Credentials are already up-to-date.');
                return;
            }

            $this->credentialListService->seed($prefix, $destinationFile);

            $credentialDatabase->setLastImportHash($hash)->setLastImportDate(date('Y-m-d H:i:s'))->save();

        } catch (\Exception $e) {

            error('An error occurred while trying to import credential list : ' . $e->getMessage());

            throw $e;

        } finally {

            if ($destinationFile && file_exists($destinationFile)) {
                unlink($destinationFile);
            }
        }
    }

    protected abstract function getPrefix();

    protected function createDestinationFile()
    {
        return tempnam(sys_get_temp_dir(), str_random(4));
    }

    protected function getCredentialDatabaseFor($prefix)
    {
        return $this->credentialDBRepo->find($prefix);
    }

}
