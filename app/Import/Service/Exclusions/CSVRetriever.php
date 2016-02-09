<?php namespace App\Import\Service\Exclusions;

use GuzzleHttp\Client;
use App\Import\Lists\ExclusionList;
use App\Import\Service\File\CsvFileReader;

/**
 * Class CSVRetriever
 * @package Import\Service\Exclusions
 */
class CSVRetriever extends Retriever
{

	/**
	 * @var	\GuzzleHttp\Client	$httpClient
	 */
	protected $httpClient;

    /**
     * @var CsvFileReader
     */
    private $fileReader;


    /**
     * Constructor
     *
     * @param   CsvFileReader $fileReader
     * @param    Client $httpClient
     */
    public function __construct(CsvFileReader $fileReader, Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->fileReader = $fileReader;
    }


    /**
	 * Retrieve the data from a remote source
	 *
     * @param	ExclusionList	$list
     * @return	ExclusionList
     */
    public function retrieveData(ExclusionList $list)
    {

        if ($this->uriIsRemote($list->uri))
        {
            $response = $this->httpClient->get($list->uri);
            $contents = $response->getBody();
        }
        else
        {
            $contents = file_get_contents($list->uri);
        }

        $filePath = storage_path('app') . '/' . $list->dbPrefix . '_temp.csv';

        file_put_contents($filePath, $contents);

        $list->data = $this->fileReader->readRecords($filePath, $list->retrieveOptions);

        if (count($list->dateColumns) > 0)
        {
            $list->data = $this->convertDatesToMysql($list->data, $list->dateColumns);
        }

        return $list;
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
