<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Page;

class CsvPage extends Page
{
	protected static $url = 'https://www.elicense.ct.gov/Lookup/FileDownload.aspx?Idnt=%d&Type=Comma';

	/**
	 * Scrape csv page
	 * @param DownloadOptionsPage $page
	 * @param string $rosterId
	 * @return static
	 */
	public static function scrape(DownloadOptionsPage $page, $rosterId)
	{
		$client = $page->getClient();
		$crawler = $client->request('GET', static::getUrl($rosterId));
	
		return new static($client, $crawler);
	}
}