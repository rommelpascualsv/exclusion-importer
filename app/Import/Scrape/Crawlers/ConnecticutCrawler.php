<?php

namespace App\Import\Scrape\Crawlers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

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
	 * Instantiate
	 * @param Client $client
	 * @param string $downloadPath
	 * @param string $downloadFileName
	 */
	public function __construct(Client $client, $downloadPath, $downloadFileName)
	{
		$this->client = $client;
		$this->downloadPath = $downloadPath;
		$this->downloadFileName = $downloadFileName;
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
	public function downloadFile()
	{	
		$mainCrawler = $this->getMainCrawler();
		$downloadOptionsCrawler = $this->getDownloadOptionsCrawler($mainCrawler);
		$rosterId = $this->getDownloadOptionsRosterId($downloadOptionsCrawler);
		
		$this->saveFile($this->getDownloadFileContent($rosterId));
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
		$form['ctl00$MainContentPlaceHolder$ckbRoster0']->tick();
		
		return $this->client->submit($form);	
	}
	
	/**
	 * Get download page roster ID 
	 * 
	 * @param Crawler $crawler
	 * @return string
	 */
	public function getDownloadOptionsRosterId(Crawler $crawler)
	{
		return $crawler->filter('input[type="submit"][value="Download"]')->attr('rosteridnt');
	}
	
	/**
	 * Get download file content
	 * @param string $rosterID
	 * @return string
	 */
	public function getDownloadFileContent($rosterID)
	{	
		$this->client->request('GET', $this->getDownloadUrl($rosterID));
		
		return $this->client->getResponse();
	}
	
	/**
	 * Save file
	 * @param string $content 
	 */
	public function saveFile($content)
	{
		if (! is_dir($this->downloadPath)) {
			mkdir($this->downloadPath, 0755, true);
		}

		file_put_contents($this->getDownloadFilePath(), $content);
	}
	
	/**
	 * Create
	 *
	 * @param string $downloadPath
	 * @param string $downloadFileName
	 * @return static
	 */
	public static function create($downloadPath, $downloadFileName)
	{
		return new static(new Client(), $downloadPath, $downloadFileName);
	}
	
	/**
	 * Get main url
	 * @return string
	 */
	protected static function getMainUrl()
	{
		return static::getUrl(static::$mainUri);
	}
	
	/**
	 * Get download url
	 * @param string $rosterId
	 * @return string
	 */
	protected static function getDownloadUrl($rosterId)
	{
		$uri = str_replace('{rosterID}', $rosterId, static::$downloadUriFormat);
		
		return static::getUrl($uri);		
	}
	
	/**
	 * Get url
	 * @param string $uri
	 * @return string
	 */
	protected static function getUrl($uri)
	{
		return static::$baseUrl . '/' . $uri;
	}
}