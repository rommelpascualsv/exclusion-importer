<?php

namespace App\Services;

use App\Services\Contracts\FileUploadServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repositories\FileUploadRepository;
use App\Events\FileImportEvent;

class FileUploadService implements FileUploadServiceInterface
{
    
    private $fileUploadRepo;
    
    public function __construct(FileUploadRepository $fileUploadRepo)
    {
        $this->fileUploadRepo = $fileUploadRepo;
    }
    
    public function uploadFile(\SplFileInfo $file, $prefix)
    {
        try {
            
            $fileName = $this->getFileNameOf($file);
            
            $filePath = $this->getFilePathFrom($fileName, $prefix);
           
            if ($this->fileUploadRepo->contains($filePath)) {
                throw new FileUploadException('A file with the same name (' . $fileName . ') already exists in the upload directory.');
            }
            
            info('Saving uploaded file for ' . $prefix . ' to ' . $filePath);
            
            $fileUrl = $this->saveFileTo($filePath, $file, $prefix);
            
            info('Successfully saved uploaded file for ' . $prefix . ' in ' . $fileUrl);
            
            return $fileUrl;
            
        } catch (\Exception $e) {
            
            error('An error occurred while trying to process uploaded file for ' . $prefix . ' : ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function getFileNameOf(\SplFileInfo $file) 
    {
        return $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename();
    }
    
    private function getFilePathFrom($fileName, $prefix)
    {
        return $prefix . DIRECTORY_SEPARATOR . $fileName;
    }
    
    private function saveFileTo($filePath, $file, $prefix)
    {
        try {
            
            $results =  $this->fileUploadRepo->create([
                'path' => $filePath,
                'contents' => file_get_contents($file)
            ]);
            
            $this->onFileSaveSucceeded($results, $prefix);
            
            return $results;
            
        } catch (\Exception $e) {
            
            $this->onFileSaveFailed($e, $filePath, $prefix);
            throw $e;
        }
    }
    
    private function onFileSaveSucceeded($filePath, $prefix)
    {
        event('file.upload.succeeded', FileImportEvent::newFileUploadSucceeded()
            ->setObjectId($prefix)
            ->setDescription(json_encode(['message' => 'Successfully saved uploaded file in ' . $filePath]))
        );
    }
    
    private function onFileSaveFailed(\Exception $e, $filePath, $prefix)
    {
        event('file.upload.failed', FileImportEvent::newFileUploadFailed()
            ->setObjectId($prefix)
            ->setDescription(json_encode([get_class($e) => 'Failed to save uploaded file in ' . $filePath . ':' . $e->getMessage()]))
        );
    }
}
