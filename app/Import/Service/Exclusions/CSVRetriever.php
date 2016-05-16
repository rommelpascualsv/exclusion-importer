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

        // Implement multiple file upload use comma searated
        $uri = $this->splitURI($list->uri);

        foreach ($uri as $key => $value) {
    
            $contents = $this->getContentFrom($value);

            $data = array_merge($data, $this->dataConverter->convertData($list, $contents));
        }

        return $data;
    }
    
    private function getContentFrom($uri)
    {
        $contents = '';
        
        if ($this->isRemoteURI($uri)) {
            $response = $this->httpClient->get($uri, ['verify' => false]);
            $contents = $response->getBody();
        } else {
            $contents = file_get_contents($uri);
        }
        
        return $contents;
    }
}
