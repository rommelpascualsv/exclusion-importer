<?php

namespace App\Import\Scrape\Crawlers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ConnecticutCrawler
{
	/**
	 * @var string
	 */
	protected static $url = 'https://www.elicense.ct.gov/Lookup/GenerateRoster.aspx';

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * Instantiate ConnecticutCrawler
	 * 
	 * @param Client $client
	 */
	public function __construct(Client $client, $downloadPath)
	{
		$this->client = $client;
		$this->downloadPath = $downloadPath;
	}
	

	public function downloadFile()
	{
		$crawler = $this->client->request('GET', static::$url);
		$form = $crawler->selectButton('Continue')->form();
		$form['ctl00$MainContentPlaceHolder$ckbRoster0']->tick();

		$crawler = $this->client->submit($form);
		$rosterID = $crawler->filter('input[type="submit"][value="Download"]')->attr('rosteridnt');

		$downloadURL = 'https://www.elicense.ct.gov/Lookup/FileDownload.aspx?Idnt=' . $rosterID . '&Type=Comma';
		$this->client->request('GET', $downloadURL);
		
		$response = $this->client->getResponse();
		$filename = str_replace('attachment;filename=', '', $response->getHeader('Content-Disposition'));
		file_put_contents(
			$this->downloadPath . '/' . $filename,
			$response->getContent()
		);
	}

	/**
	 * Create
	 * 
	 * @return static
	 */
	public static function create()
	{
		return new static(new Client(), storage_path('app'));		
	}
}