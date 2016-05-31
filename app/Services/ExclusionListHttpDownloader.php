<?php

namespace App\Services;

use App\Import\Lists\ExclusionList;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

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
        'xml',
        'zip',
        'html'
    ];
    
    /**
     * The number of seconds to wait while trying to connect to a server. Defaults
     * to 10 seconds.
     * @var float
     */
    private $connectTimeout = 10; 
    
    /**
     * The default request options to send in every request. Gets overriden
     * by any options specified in ExclusionList->requestOptions. Defaults to
     * @var array 
     * [
     *    'verify' => false,
     *    'connect_timeout' => $this->connectTimeout
     * ]
     */
    private $defaultRequestOptions;
    
    /**
     * The default HTTP method (i.e. GET, POST) to use if no http_method is 
     * present in the ExclusionList's requestOptions field. Defaults to 'GET'
     * @var string 
     */
    private $defaultHttpMethod = 'GET';
    
    /**
     * The number of times this class will retry to connect to a given uri before
     * giving up
     * @var int
     */
    private $maxRetries = 3;
    
    public function __construct()
    {
        if (! $this->downloadDirectory) {
            $this->setDownloadDirectory(storage_path(self::DEFAULT_DOWNLOAD_DIRECTORY));
        }
        
        if (! $this->defaultRequestOptions) {
            
            $this->defaultRequestOptions = [
                'verify' => false,
                'connect_timeout' => $this->connectTimeout
            ];
        }
    }    
    
    public function supports($type)
    {
        return $type && array_search($type, $this->downloadableTypes) !== false;
    }
    
    public function downloadFiles(ExclusionList $exclusionList)
    {
        $this->createDownloadDirectoryIfNotExists();

        if (! $this->supports($exclusionList->type)) {
            return null;    
        }
        
        $uris = $this->splitURI($exclusionList->uri);
        
        if (! $uris) {
            return null;    
        }
        
        $localFilePaths = [];
        
        foreach ($uris as $uriIndex => $uri) {
            
            if ($this->isRemoteURI($uri)) {
                
                $downloadDestFile = $this->getDownloadDestFile($uriIndex, $exclusionList);
                
                info('Downloading file from '. $uri . ' to ' . $downloadDestFile);
                
                $localFilePaths[] = $this->download($uri, $downloadDestFile, $exclusionList);
                
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
    
    public function getDefaultRequestOptions()
    {
        return $this->defaultRequestOptions;
    }
    
    public function setDefaultRequestOptions($options)
    {
        $this->defaultRequestOptions = $options;
    }
    
    public function getDefaultHttpMethod()
    {
        return $this->defaultHttpMethod;
    }
    
    public function setDefaultHttpMethod($method)
    {
        $this->defaultHttpMethod = $method;
    }    
    
    public function getMaxRetries()
    {
        return $this->maxRetries;
    }
    
    public function setMaxRetries($maxRetries)
    {
        $this->maxRetries = $maxRetries;
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
    
    private function getDownloadDestFile($fileIndex, ExclusionList $exclusionList)
    {
        return "{$this->getDownloadDirectory()}/{$exclusionList->dbPrefix}-{$fileIndex}.{$exclusionList->type}";        
    }
    
    private function download($uri, $downloadDestFile, ExclusionList $exclusionList)
    {
        $httpMethod = $exclusionList->requestOptions && isset($exclusionList->requestOptions['http_method']) ? $exclusionList->requestOptions['http_method'] : $this->defaultHttpMethod;
        
        $requestOptions = $exclusionList->requestOptions ? array_merge($this->defaultRequestOptions, $exclusionList->requestOptions) : $this->defaultRequestOptions;

        $client = $this->createClient($uri, $downloadDestFile);
        
        $client->request($httpMethod, $exclusionList->urlSuffix, $requestOptions);
        
        return $downloadDestFile;
    }
    
    private function createClient($uri, $downloadDestFile)
    {
        return new Client([
            'base_uri' => $uri,
            'sink'     => $downloadDestFile,
            'handler'  => $this->createHandlerStack()
        ]);
    }
    
    private function createHandlerStack()
    {
        $handlerStack = HandlerStack::create(new CurlHandler());
        $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
        return $handlerStack;
    }
    
    private function retryDecider() {
        
        return function ($retries, Request $request, Response $response = null, RequestException $exception = null) {

            if ($retries >= $this->maxRetries) {
                return false;
            }

            // Retry connection exceptions
            if( $exception instanceof ConnectException ) {
                return true;
            }

            if( $response ) {
                // Retry on server errors
                if( $response->getStatusCode() >= 500 ) {
                    return true;
                }
            }

            return false;
        };
    }
    
    private function retryDelay() {
        return function( $numberOfRetries ) {
            return 1000 * $numberOfRetries;
        };
    }    
}
