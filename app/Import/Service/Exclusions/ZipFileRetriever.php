<?php namespace App\Import\Service\Exclusions;

use GuzzleHttp\Client;
use App\Import\Lists\ExclusionList;
use App\Import\Service\DataCsvConverter;

/**
 * Class CSVRetriever
 * @package Import\Service\Exclusions
 */
class ZipFileRetriever extends Retriever
{

    /**
     * @var \App\Import\Service\DataCsvConverter
     */
    protected $dataConverter;


	/**
	 * @var	\GuzzleHttp\Client	$httpClient
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
     * @param	ExclusionList	$list
     * @return	ExclusionList
     */
    public function retrieveData(ExclusionList $list)
    {

        copy($list->uri, storage_path('app') . '/' . $list->dbPrefix . '.zip');

        $contents = file_get_contents('zip://' . storage_path('app') . '/' . $list->dbPrefix . '.zip#2016-01-31, PI term-suspend-probation.xlsx');

        $list->data = $this->dataConverter->convertData($list, $contents);

        if (count($list->dateColumns) > 0)
        {
            $list->data = $this->convertDatesToMysql($list->data, $list->dateColumns);
        }

        return $list;
    }
}
