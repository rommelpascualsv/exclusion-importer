<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;

class CsvDir
{
    /**
     * Get data from filesystem
     * @param ScrapeFilesystemInterface $filesystem
     * @param string $dir
     */
    public static function getDataFromFilesystem(
        ScrapeFilesystemInterface $filesystem,
        $dir = 'csv/connecticut'
    ) {
        $directories = $filesystem->listContents($dir);
        $data = [];
        
        foreach ($directories as $dirData) {
            $dirFiles = $filesystem->listContents($dirData['path']);
            	
            foreach ($dirFiles as $fileData) {
                $data[] = [
                    'category' => $dirData['basename'],
                    'option' => $fileData['filename'],
                    'file_path' => $filesystem->getPath($fileData['path'])
                ];
            }
        }
        
        return $data;
    }
}