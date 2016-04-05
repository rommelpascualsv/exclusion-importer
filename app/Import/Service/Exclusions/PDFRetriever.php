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

    public function retrieveData(ExclusionList $list)
    {
        $folder = storage_path('app');

        $file = "{$folder}/{$list->dbPrefix}.pdf";

        $this->httpClient->get($list->uri, ['sink' => $file]);

        $contents = shell_exec($list->pdfToText . ' ' . $file . ' -');

        return $contents;
    }
}
