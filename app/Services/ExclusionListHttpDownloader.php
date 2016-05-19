<?php

namespace App\Services;

use App\Import\Lists\ExclusionList;
use GuzzleHttp\Client;

class ExclusionListHttpDownloader
{
    private $downloadDirectory = null;
    
    private $downloadableTypes = [
        'csv',
        'pdf',
        'tsv',
        'txt',
        'xls',
        'xlsx',
        'xml',
        'zip'
    ];
    
    public function __construct()
    {
        if (! $this->downloadDirectory) {
            $this->setDownloadDirectory(storage_path('app/imports/latest'));
        }
    }    
    
    public function downloadFiles(ExclusionList $exclusionList)
    {
        $this->createDownloadDirectoryIfNotExists();

        if (! $this->isDownloadableType($exclusionList->type)) {
            return null;    
        }
        
        $localFilePaths = [];
        
        $uris = $this->splitURI($exclusionList->uri);
        
        foreach ($uris as $uriIndex => $uri) {
            
            if ($this->isRemoteURI($uri)) {
                info('Downloading file from '. $uri . ' to ' . $this->getDownloadDirectory());
                $localFilePaths[] = $this->downloadToDownloadDirectoryFrom($uri, $uriIndex, $exclusionList);
            } else {
                $localFilePaths[] = $uri;
            }
        }

        return $localFilePaths;
        
    }
    
    public function getDownloadDirectory()
    {
        return $this->downloadDirectory;    
    }
    
    public function setDownloadDirectory($downloadDirectory)
    {
        $this->downloadDirectory = $downloadDirectory;    
    }
    
    public function getDownloadableTypes()
    {
        return $this->downloadableTypes;
    }

    public function setDownloadableTypes(array $downloadableTypes = [])
    {
        $this->downloadableTypes = $downloadableTypes;
    }
    
    private function createDownloadDirectoryIfNotExists()
    {
        if(! is_dir($this->downloadDirectory)) {
            mkdir($this->downloadDirectory, 777, true);
        } 
    }
    
    
    private function splitURI($uri)
    {
        $url = explode(',', $uri);
    
        return array_map(function ($item) {
            return trim($item);
        }, $url);
    }
    
    private function isRemoteURI($uri)
    {
        return filter_var($uri, FILTER_VALIDATE_URL);
    }
    
    private function isDownloadableType($type)
    {
        return array_search($type, $this->downloadableTypes) !== false;
    }
    
    private function downloadToDownloadDirectoryFrom($uri, $fileIndex, ExclusionList $exclusionList)
    {
        
        $downloadDestFile = "{$this->getDownloadDirectory()}/{$exclusionList->dbPrefix}-{$fileIndex}.{$exclusionList->type}";
        
        $client = new Client([
            'base_uri' => $uri,
            'sink'     => $downloadDestFile,
            'verify'   => false
        ]);
        
        $httpMethod = $exclusionList->requestOptions && isset($exclusionList->requestOptions['http_method']) ? $exclusionList->requestOptions['http_method'] : 'GET';
        
        $client->request($httpMethod, $exclusionList->urlSuffix, $exclusionList->requestOptions);
        
        return $downloadDestFile;
    }
}