<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Page;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use App\Exceptions\Scrape\Connecticut\DownloadOptionMissingException;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;

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
	 * @param Option $option
	 * @return string
	 */
	public function getRosterId(Option $option)
	{
		$optionColNode = $this->crawler->filterXPath('//td[text() = "' . $option->getName() . '"]');
		
		if ($optionColNode->count() == 0) {
			throw new DownloadOptionMissingException($option->getDescriptiveName() . ' download option cannot be found');
		}
		
		$downloadButtonNode = $this->getNodesByCss(
				'input[type="submit"][value="Download"]',
				$option->getDescriptiveName() . ' Download button cannot be found',
				$optionColNode->parents()
		);
		
		return $downloadButtonNode->attr('rosteridnt');
	}
}