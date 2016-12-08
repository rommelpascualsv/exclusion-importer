<?php

namespace App\Services;

use App\Events\CredentialEventFactory;
use App\Exceptions\LoggablePDOException;
use App\Import\CredentialLists\CredentialFileMakerFactory;
use App\Seeders\SeederFactory;
use App\Services\Contracts\CredentialListServiceInterface;

class CredentialListService implements CredentialListServiceInterface
{
    private $credentialFileMakerFactory;
    private $seederFactory;
    private $fileHelper;

    public function __construct(CredentialFileMakerFactory $credentialFileMakerFactory,
                                SeederFactory $seederFactory,
                                FileHelper $fileHelper)
    {
        $this->credentialFileMakerFactory = $credentialFileMakerFactory;
        $this->seederFactory = $seederFactory;
        $this->fileHelper = $fileHelper;
    }

    public function createCredentialDatabaseFile($prefix, $sourceUri, $destinationFile)
    {
        try {

            info('Generating credential list file in ' . $destinationFile . ' for ' . $prefix . ' from ' . $sourceUri);

            $fileType = $this->writeCredentialDataTo($destinationFile, $prefix, $sourceUri);

            info('Credential file generated for ' . $prefix . '; Saving to repository...');

            $hash = $this->saveCredentialFile($destinationFile, $fileType,  $prefix);

            info('Credential file successfully saved for ' . $prefix . ' with hash : ' . $hash);

            return $hash;

        } catch (\PDOException $e) {

            $this->onFileCreationException($prefix, new LoggablePDOException($e));
            throw $e;

        } catch (\Exception $e) {

            $this->onFileCreationException($prefix, $e);
            throw $e;
        }
    }

    public function seed($prefix, $credentialFile)
    {
        try {

            info('Seeding ' . $prefix . ' with ' . $credentialFile);

            $seeder = $this->seederFactory->createSeeder($prefix);

            $results = $seeder->seed($credentialFile);

            $this->onCredentialSeedingSucceeded($prefix, $results);

            info('Successfully seeded ' . $prefix . ' with file ' . $credentialFile);

            return $results;

        }  catch (\PDOException $e) {

            $this->onCredentialSeedingException($prefix, $e);
            $this->onCredentialSeedingFailed($prefix, new LoggablePDOException($e));
            throw $e;

        } catch (\Exception $e) {

            $this->onCredentialSeedingException($prefix, $e);
            $this->onCredentialSeedingFailed($prefix, $e);
            throw $e;
        }

    }

    private function writeCredentialDataTo($destinationFile, $prefix, $sourceUri)
    {
        try {

            $credentialFileMaker = $this->credentialFileMakerFactory->createCredentialFileMaker($prefix, $sourceUri);

            $credentialFileMaker->buildFile($destinationFile);

            $this->onFileCreationSucceeded($prefix);

            return $credentialFileMaker->getFileType();

        } catch (\PDOException $e) {

            $this->onFileCreationFailed($prefix, new LoggablePDOException($e));
            throw $e;

        } catch (\Exception $e) {

            $this->onFileCreationFailed($prefix, $e);
            throw $e;
        }
    }

    private function saveCredentialFile($credentialFile, $fileType, $prefix)
    {
        try {

            // Save the file hash first...
            $hash = $this->fileHelper->createAndSaveFileHash($credentialFile, $fileType, $prefix);

            // Then save the actual contents of the file
            $updated = $this->fileHelper->saveFileContents($credentialFile, $hash, $prefix);

            $this->onFileSaveSucceeded($prefix, $updated ? ['fileHash' => $hash] : ['message' => 'Last saved file is already up-to-date']);

            return $hash;

        } catch (\PDOException $e) {

            $this->onFileSaveFailed($prefix, new LoggablePDOException($e));
            throw $e;

        } catch (\Exception $e) {

            $this->onFileSaveFailed($prefix, $e);
            throw $e;
        }
    }

    private function onFileCreationSucceeded($prefix)
    {
        event('credential.file.creation.succeeded', CredentialEventFactory::newFileCreationSucceeded()->setObjectId($prefix));
    }

    private function onFileCreationFailed($prefix, \Exception $e)
    {
        event('credential.file.creation.failed', CredentialEventFactory::newFileCreationFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to create credential file : ' . $e->getMessage()]))
        );
    }

    private function onFileSaveSucceeded($prefix, $results = null)
    {
        $eventPayload = CredentialEventFactory::newFileSaveSucceeded()->setObjectId($prefix);

        if ($results) {
            $eventPayload->setDescription(json_encode($results));
        }

        event('credential.file.save.succeeded', $eventPayload);
    }

    private function onFileSaveFailed($prefix, \Exception $e)
    {
        event('credential.file.save.succeeded', CredentialEventFactory::newFileSaveFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to create credential file : ' . $e->getMessage()]))
        );
    }

    private function onFileCreationException($prefix, \Exception $e)
    {
        error('An error occurred while trying to create credential list file for ' . $prefix . ' : ' . $e->getMessage());
    }

    private function onCredentialSeedingSucceeded($prefix, $results)
    {
        event('credential.seed.succeeded', CredentialEventFactory::newCredentialSeedingSucceeded()
            ->setObjectId($prefix)
            ->setDescription(json_encode($results))
        );
    }

    private function onCredentialSeedingFailed($prefix, \Exception $e)
    {
        event('credential.seed.failed', CredentialEventFactory::newCredentialSeedingFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to seed credentials : ' . $e->getMessage()]))
        );
    }

    private function onCredentialSeedingException($prefix, \Exception $e)
    {
        error('An error occurred while trying to seed credentials for ' . $prefix . ' : ' . $e->getMessage());
    }
}
