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
            
            if (strpos($list->pdfToText, "pdftotext") !== false) {
                $contents = shell_exec($list->pdfToText . ' ' . $file . ' -');
            } else {
                $contents = shell_exec($list->pdfToText . ' ' . $file);
            }
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
}
