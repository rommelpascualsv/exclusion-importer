<?php namespace App\Import\Service\Exclusions;

use App\Import\Lists\ExclusionList;
use GuzzleHttp\Client;

class PDFRetriever extends Retriever
{
    /**
     * @param \GuzzleHttp\Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @var    \GuzzleHttp\client $httpClient
     */
    protected $httpClient;

    protected $url;

    public function retrieveData(ExclusionList $list)
    {
        $data = [];
        // Implement multiple file upload use comma searated
        $uri = $this->splitURI($list->uri);

        foreach ($uri as $key => $value) {
            
            $file = $this->getPdfFileFrom($value, $key, $list);
            
            $contents = shell_exec($this->normalizePaths($list->pdfToText) . ' ' . $file. ' 2>/dev/null');
            // Merge Data
            $data[] = $contents;
        }

        // If single item return array element
        if (count($data) === 1) {
            return $data[0];
        }

        return $data;
    }

    private function getPdfFileFrom($uri, $fileIndex, $list)
    {
        $file = null;

        if ($this->isRemoteURI($uri)) {
            $folder = storage_path('app');
            $file = "{$folder}/{$list->dbPrefix}-{$fileIndex}.pdf";
            $this->httpClient->get($uri, ['sink' => $file]);
        } else {
            $file = $uri;
        } 
        
        return $file;
    }
    
    /**
     * Converts relative paths in the cmd string to their equivalent absolute paths
     * @param string $cmd
     */
    private function normalizePaths($cmd)
    {
        return str_replace(' ../', ' '.base_path().'/', $cmd);
    }
}
