<?php

namespace App\Utils;

use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Adapter\Local;

class FileSystemUtils
{
    /**
     * Get the URL for the file at the given path.
     *
     * @param string $path            
     * @return string
     */
    public static function url($path)
    {
        $adapter = app('filesystem')->getAdapter();
        
        if ($adapter instanceof AwsS3Adapter) {
            
            $path = $adapter->getPathPrefix() . $path;
            
            return $adapter->getClient()->getObjectUrl($adapter->getBucket(), $path);
            
        } elseif ($adapter instanceof Local) {
            
            return $adapter->applyPathPrefix($path);
            
        } else {
            throw new \RuntimeException('Current file system driver does not support retrieving URLs.');
        }
    }
}
