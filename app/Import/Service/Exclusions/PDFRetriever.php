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
        $uri = $this->multipleUri($list->uri);

        foreach ($uri as $key => $value) {
            $folder = storage_path('app');

            if ($this->uriIsRemote($value)) {
                $file = "{$folder}/{$list->dbPrefix}-{$key}.pdf";
                $this->httpClient->get($value, ['sink' => $file]);
            } else {
                $file = $value;
            }

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

    /**
     * @param $uri
     * @return mixed
     */
    private function uriIsRemote($uri)
    {
        return filter_var($uri, FILTER_VALIDATE_URL);
    }
}
