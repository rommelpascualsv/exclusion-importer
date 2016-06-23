<?php

namespace App\Console\Commands\NJCredential;

use App\Services\CredentialListService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Import extends Command
{
    
    protected $name = 'njcredential:import';

    protected $description = 'Import the NJ Credential Database to a storage location';
    
    private $credentialListService;
    
    public function __construct(CredentialListService $credentialListService) 
    {
        parent::__construct();
        $this->credentialListService = $credentialListService;
    }

    public function fire()
    {
        try {
            
            $this->credentialListService->generateCredentialListFile('njcredential', $this->getDestinationFilePath());
            
        } catch (\Exception $e) {
            
            error('An error occurred while trying to import credential list : ' . $e->getMessage());
            
            throw $e;
        }
    }
    
    protected function getDestinationFilePath()
    {
        return $this->argument('destFilePath') ? storage_path($this->argument('destFilePath')) : null;
    }

    public function getArguments()
    {
        return [
            ['destFilePath', InputArgument::OPTIONAL, 'The destination file path for the imported list']
        ];
    }
}
