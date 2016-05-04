<?php

namespace App\Import\Scrape\Crawlers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Import\Scrape\Data\ConnecticutCategories;

class ConnecticutCrawler
{
	/**
	 * @var Client
	 */
	protected $client;
	
	/**
	 * @var string
	 */
	protected $downloadPath;
	
	/**
	 * @var string
	 */
	protected $downloadFileName;
	
	/**
	 * @var string
	 */
	protected static $baseUrl = 'https://www.elicense.ct.gov/Lookup';
	
	/**
	 * @var string
	 */
	protected static $mainUri = 'GenerateRoster.aspx';
	
	/**
	 * @var string
	 */
	protected static $downloadUriFormat = 'FileDownload.aspx?Idnt={rosterID}&Type=Comma';
	
	/**
	 * @var array
	 */
	protected static $selectors = [
			'main' => [
				'category_header' => '.panel-heading'
			],
			'download_options' => [
					'download_button' => 'input[type="submit"][value="Download"]'
			]
	];
	
	/**
	 * @var array
	 */
	protected static $xpath = [
			'download_options' => [
					'column' => '//td[text() = "%s"]'
			]
	];
	
	/**
	 * Instantiate
	 * @param Client $client
	 * @param string $downloadPath
	 * @param array $options
	 */
	public function __construct(Client $client, $downloadPath, array $options)
	{
		$this->client = $client;
		$this->downloadPath = $downloadPath;
		$this->options = $options;
	}
	
	/**
	 * Get client
	 * 
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}
	
	/**
	 * Get options
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Get download file path
	 * @return string
	 */
	public function getDownloadFilePath()
	{
		return $this->downloadPath . '/' . $this->downloadFileName;
	}
	
	/**
	 * Download file
	 */
	public function downloadFiles()
	{	
		$mainCrawler = $this->getMainCrawler();
		$downloadOptionsCrawler = $this->getDownloadOptionsCrawler($mainCrawler);
		
		$this->createDownloadPath();
		
		foreach ($this->options as $option) {
			$rowCrawler = $downloadOptionsCrawler->filterXPath(
				static::getXpath('download_options', 'column', $option['label'])
			);
			$rosterId = $rowCrawler->parents()
				->filter(static::getSelector('download_options', 'download_button'))
				->attr('rosteridnt');			
			$fileContent = $this->getDownloadFileContent($rosterId);
			
			$this->saveDownloadFile($option['file_name'], $fileContent);
		}
	}
	
	/**
	 * Get main page crawler
	 * 
	 * @return Crawler
	 */
	public function getMainCrawler()
	{
		return $this->client->request('GET', static::getMainUrl());
	}
	
	/**
	 * Get download options crawler
	 * 
	 * @param Crawler $crawler
	 * @return Crawler
	 */
	public function getDownloadOptionsCrawler(Crawler $crawler)
	{
		$form = $crawler->selectButton('Continue')->form();
		
		foreach ($this->options as $data) {
			$fieldName = $data['field_name'];
			$form[$fieldName]->tick();
		}
		
		return $this->client->submit($form);	
	}
	
	/**
	 * Get download file content
	 * @param string $rosterID
	 * @return string
	 */
	public function getDownloadFileContent($rosterID)
	{	
		$this->client->request('GET', $this->getDownloadUrl($rosterID));
		
		return $this->client->getResponse()->getContent();
	}
	
	/**
	 * Save file
	 * @param string $content 
	 */
	public function saveDownloadFile($fileName, $content)
	{
		file_put_contents(
				$this->downloadPath . DIRECTORY_SEPARATOR . $fileName . '.csv',
				$content
		);
	}
	
	/**
	 * Create download path
	 */
	protected function createDownloadPath()
	{
		if (! is_dir($this->downloadPath)) {
			mkdir($this->downloadPath, 0755, true);
		}
	}
	
	/**
	 * Create
	 * @param string $downloadPath
	 * @param array $optionKeys
	 * @return \App\Import\Scrape\Crawlers\ConnecticutCrawler
	 */
	public static function create($downloadPath, array $optionKeys)
	{
		$client = new Client([
			'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
		]);
		$options = ConnecticutCategories::getOptions($optionKeys);
		
		return new static($client, $downloadPath, $options);
	}
	
	/**
	 * Get url
	 * @param string $uri
	 * @return string
	 */
	public static function getUrl($uri)
	{
		return static::$baseUrl . '/' . $uri;
	}
	
	/**
	 * Get main url
	 * @return string
	 */
	public static function getMainUrl()
	{
		return static::getUrl(static::$mainUri);
	}
	
	/**
	 * Get download url
	 * @param string $rosterId
	 * @return string
	 */
	public static function getDownloadUrl($rosterId)
	{
		$uri = str_replace('{rosterID}', $rosterId, static::$downloadUriFormat);
		
		return static::getUrl($uri);		
	}
	
	/**
	 * Get selector
	 * @param string $page
	 * @param string $type
	 * @return string
	 */
	public static function getSelector($page, $type)
	{
		return static::$selectors[$page][$type];
	}
	
	/**
	 * Get xpath
	 * @param string $page
	 * @param string $type
	 * @param string|array $args
	 */
	public static function getXpath($page, $type, $args)
	{
		$xpath = static::$xpath[$page][$type];
		
		if (! is_array($args)) {
			$args = [$args];
		}
		
		return vsprintf($xpath, $args);
	}
}