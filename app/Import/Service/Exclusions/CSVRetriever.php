<?php namespace App\Import\Service\Exclusions;

use GuzzleHttp\Client;
use App\Import\Lists\ExclusionList;
use App\Import\Service\DataCsvConverter;

/**
 * Class CSVRetriever
 * @package Import\Service\Exclusions
 */
class CSVRetriever extends Retriever
{
    /**
     * @var \App\Import\Service\DataCsvConverter
     */
    protected $dataConverter;

    /**
     * @var \GuzzleHttp\Client  $httpClient
     */
    protected $httpClient;

    /**
     * Constructor
     *
     * @param    DataCsvConverter $dataConverter
     * @param    Client $httpClient
     */
    public function __construct(DataCsvConverter $dataConverter, Client $httpClient)
    {
        $this->dataConverter = $dataConverter;
        $this->httpClient = $httpClient;
    }

    /**
     * Retrieve the data from a remote source
     *
     * @param   ExclusionList   $list
     * @return  ExclusionList
     */
    public function retrieveData(ExclusionList $list)
    {
        $data = [];
        $url = explode(',', $list->uri);

        $list->uri = array_map(function ($item) {
            return trim($item);
        }, $url);

        foreach ($list->uri as $key => $value) {

            if ($this->uriIsRemote($value)) {
                $response = $this->httpClient->get($value);
                $contents = $response->getBody();
            } else {
                $contents = file_get_contents($value);
            }

            $data[] = $this->dataConverter->convertData($list, $contents);
        }

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
