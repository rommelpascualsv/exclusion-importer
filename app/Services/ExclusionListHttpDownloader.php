<?php

namespace App\Services;

use App\Import\Lists\ExclusionList;
use GuzzleHttp\Client;

class ExclusionListHttpDownloader
{
    /**
     * The default download directory path relative to the 'storage' directory
     */
    const DEFAULT_DOWNLOAD_DIRECTORY = 'app/import/tmp';
    
    private $downloadDirectory = null;
    
    private $downloadableTypes = [
        'csv',
        'pdf',
        'tsv',
        'txt',
        'xls',
        'xlsx',
        'xml'
        //,'zip'
    ];
    
    /**
     * Tthe number of seconds to wait while trying to connect to a server. Defaults
     * to 10 seconds.
     * @var float
     */
    private $connectTimeout = 10; 
    
    public function __construct()
    {
        if (! $this->downloadDirectory) {
            $this->setDownloadDirectory(storage_path(self::DEFAULT_DOWNLOAD_DIRECTORY));
        }
    }    
    
    public function supports(ExclusionList $exclusionList)
    {
        return $exclusionList && array_search($exclusionList->type, $this->downloadableTypes) !== false;
    }
    
    public function downloadFiles(ExclusionList $exclusionList)
    {
        $this->createDownloadDirectoryIfNotExists();

        if (! $this->supports($exclusionList)) {
            return null;    
        }
        
        $uris = $this->splitURI($exclusionList->uri);
        
        if (! $uris) {
            return null;    
        }
        
        $localFilePaths = [];
        
        foreach ($uris as $uriIndex => $uri) {
            
            if ($this->isRemoteURI($uri)) {
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
    
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }
    
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;
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
    
    private function downloadToDownloadDirectoryFrom($uri, $fileIndex, ExclusionList $exclusionList)
    {
        $downloadDestFile = "{$this->getDownloadDirectory()}/{$exclusionList->dbPrefix}-{$fileIndex}.{$exclusionList->type}";

        info('Downloading file from '. $uri . ' to ' . $downloadDestFile);

        $client = new Client([
            'base_uri' => $uri,
            'sink'     => $downloadDestFile
        ]);
        
        $httpMethod = $exclusionList->requestOptions && isset($exclusionList->requestOptions['http_method']) ? $exclusionList->requestOptions['http_method'] : 'GET';
        
        $requestOptions = $exclusionList->requestOptions ? array_merge($this->getDefaultRequestOptions(), $exclusionList->requestOptions) : $this->getDefaultRequestOptions();

        $client->request($httpMethod, $exclusionList->urlSuffix, $requestOptions);
        
        return $downloadDestFile;
    }
    
    private function getDefaultRequestOptions()
    {
        return ['verify' => false, 'connect_timeout' => $this->connectTimeout];        
    }
}