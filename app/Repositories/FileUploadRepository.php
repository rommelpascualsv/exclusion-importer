<?php

namespace App\Repositories;

class FileUploadRepository implements Repository
{
    const DEFAULT_UPLOAD_ROOT_DIRECTORY = 'exclusion-lists';
    
    private $uploadRootDirectory;
    private $fileSystem;
    
    public function __construct()
    {
        $this->uploadRootDirectory = self::DEFAULT_UPLOAD_ROOT_DIRECTORY;
        $this->fileSystem = app('filesystem');
    }
    
    public function create($record)
    {
        $file = $this->uploadRootDirectory . DIRECTORY_SEPARATOR . $record['path'];
        
        $this->fileSystem->put($file, $record['contents']);
        
        return $this->fileSystem->getAdapter()->applyPathPrefix($file);
    }
    
    public function clear()
    {
        // TODO : Implement me 
    }
    
    public function find($file)
    {
        // TODO : Implement me
    }
    
    public function contains($file)
    {
        return $this->fileSystem->has($this->uploadRootDirectory . DIRECTORY_SEPARATOR . $file);        
    }
    
    public function getUploadRootDirectory()
    {
        return $this->uploadRootDirectory;
    }
    
    public function setUploadRootDirectory($uploadRootDirectory)
    {
        $this->uploadRootDirectory = $uploadRootDirectory;
        return $this;
    }    
}
