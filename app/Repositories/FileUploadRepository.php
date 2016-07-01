<?php

namespace App\Repositories;

use App\Utils\FileSystemUtils;
use Illuminate\Contracts\Filesystem\Filesystem;

class FileUploadRepository implements Repository
{
    const DEFAULT_UPLOAD_ROOT_DIRECTORY = 'exclusion-lists';
    
    private $uploadRootDirectory;
    private $fileSystem;
    
    public function __construct(Filesystem $fileSystem)
    {
        $this->uploadRootDirectory = self::DEFAULT_UPLOAD_ROOT_DIRECTORY;
        $this->fileSystem = $fileSystem;
    }
    
    public function create($record)
    {
        $file = $this->uploadRootDirectory . DIRECTORY_SEPARATOR . $record['path'];
        
        $this->fileSystem->put($file, $record['contents']);
        
        return FileSystemUtils::url($file);
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
