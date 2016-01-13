<?php namespace App\Import\Lists;

use App\Import\Service\DataCsvConverter;
use App\Import\Service\Exclusions\CSVRetriever;
use App\Import\Service\Exclusions\HTMLRetriever;
use App\Import\Service\Exclusions\PDFRetriever;
use App\Import\Service\File\CsvFileReader;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ExclusionList
{

	public function __construct($listPrefix)
	{
		$configs = config("import.$listPrefix");
		foreach($configs as $key => $config)
		{
			$this->$key = $config;
		}
		$this->retriever = $this->getRetriever($this->retrieverType);
	}

	/**
	 * Database table's prefix (e.g. {$dbPrefix}_records)
	 *
	 * @var	string
	 */
	public $dbPrefix;


	/**
	 * File's public uri
	 *
	 * @var	string
	 */
	public $uri;


	/**
	 * @var	array
	 */
	public $retrieveOptions = array();


	/**
	 * @var	array
	 */
	public $headerOptions = array();


	/**
	 * @var	array
	 */
	public $data = array();


	/**
	 * @var
	 */
	public $fileHeaders;


	/**
	 * Columns to create a hash from
	 *
	 * @var	array
	 */
	public $hashColumns = [];


	public $dateColumns = [];


	public $fieldNames = [];


	public $urlSuffix = '';


	public $requestOptions = [];

	public $shouldHashListName = false;

	public function loadData()
	{
		$this->data = $this->retriever->retrieveData($this);
	}

	private function getRetriever($retrieverType)
	{
		switch($retrieverType) {
			case 'pdf':
				return new PDFRetriever(new Client());

				break;

			case 'csv';
				return new CSVRetriever(
					new DataCsvConverter(new CsvFileReader()),
					new Client()
				);
				break;

			case 'html':
				return new HTMLRetriever(
					new Crawler(),
					new DataCsvConverter(new CsvFileReader())
				);

				break;
		}
	}

}
