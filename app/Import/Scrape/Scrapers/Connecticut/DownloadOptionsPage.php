<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Page;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;

class DownloadOptionsPage extends Page
{		
	/**
	 * Scrape main page
	 * @param Client $client
	 * @return static
	 */
	public static function scrape(MainPage $mainPage, OptionCollection $options)
	{
		$form = $mainPage->tickOptions($options);
		$client = $mainPage->getClient();
		$crawler = $client->submit($form);
		
		return new static($client, $crawler);
	}
	
	/**
	 * Get roster ID
	 * @param string $name
	 * @return string
	 */
	public function getRosterId($name)
	{
		$optionColNode = $this->getNodesByXpath(
				'//td[text() = "' . $name . '"]',
				$name . ' download option column cannot be found'
		);
		
		$downloadButtonNode = $this->getNodesByCss(
				'input[type="submit"][value="Download"]',
				'Download button cannot be found for ' . $name,
				$optionColNode->parents()
		);
		
		return $downloadButtonNode->attr('rosteridnt');
	}
}