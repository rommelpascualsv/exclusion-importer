<?php

namespace App\Services;

use App\Services\Contracts\CredentialListServiceInterface;
use App\Repositories\CredentialDatabaseRepository;
use App\Import\CredentialLists\CredentialFileMakerFactory;

class CredentialListService implements CredentialListServiceInterface
{
    private $credentialDBRepo;
    private $credentialFileMakerFactory;
    
    public function __construct(CredentialDatabaseRepository $credentialDBRepo,
        CredentialFileMakerFactory $credentialFileMakerFactory)
    {
        $this->credentialDBRepo = $credentialDBRepo;
        $this->credentialFileMakerFactory = $credentialFileMakerFactory;
    }
    
    public function generateCredentialListFile($prefix, $destinationFilePath = null)
    {
        if (! $prefix) {
            throw new CredentialListServiceException('No credential list prefix specified');
        }
        
        $destinationFilePath = $this->resolveDestinationFilePath($prefix, $destinationFilePath);
        
        $this->createCredentialListFile($prefix, $destinationFilePath);
        
        return $destinationFilePath;
    }
    
    private function createCredentialListFile($prefix, $destinationFilePath)
    {
        $credentialFileMaker = $this->createCredentialFileMaker($prefix, $destinationFilePath);
        
        info('Creating credential list for \'' . $prefix . '\'...');
        
        $credentialFileMaker->buildFile();
    }
    
    private function createCredentialFileMaker($prefix, $destinationFilePath)
    {
        $credentialFileMaker = $this->credentialFileMakerFactory->createCredentialFileMaker($prefix, $destinationFilePath);
        
        if (! $credentialFileMaker) {
            throw new CredentialListServiceException('Unable to create a credential file maker for \'' . $prefix . '\'');
        }
        
        return $credentialFileMaker;
    }
    
    /**
     * Returns the destination file path configured in the credential database
     * repository for the given prefix if destinationFilePath is null, otherwise
     * returns destionationFilePath.
     * 
     * @param prefix the credential list prefix
     * @param destinationFilePath the user-specified destination file path. If null,
     * this method will return the destination file path of the given prefix
     * from the credential database repository. If not null, then this method
     * just returns its value.
     */
    private function resolveDestinationFilePath($prefix, $destinationFilePath)
    {
        $destinationFilePath = $destinationFilePath ?: $this->getDestinationFilePathFromRepo($prefix);
        
        if ($destinationFilePath) {
            return $destinationFilePath;
        }
        
        throw new CredentialListServiceException('No destination path specified for \''. $prefix .'\'');
    }

    private function getDestinationFilePathFromRepo($prefix)
    {
        $record = $this->credentialDBRepo->find($prefix);

        return $record ? $record->storage_path : null;
    }
}
