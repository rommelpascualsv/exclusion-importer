<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Page;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;

class MainPage extends Page
{	
	/*
	 * @var string
	 */
	protected static $url = 'https://www.elicense.ct.gov/Lookup/GenerateRoster.aspx';
	
	public function tickOptions(OptionCollection $options)
	{
		$buttonNode = $this->crawler->selectButton('Continue');
		
		if ($buttonNode->count() == 0) {
			throw new \Exception('Continue button cannot be found in the main page.');
		}
		
		$form = $buttonNode->form();
		
		foreach ($options as $option) {
			$fieldName = $option->getFieldName();
			
			if (empty($form[$fieldName])) {
				throw new \Exception($option->getName() . ' option cannot be found.');
			}
			
			$form[$fieldName]->tick();
		}
		
		return $form;
	}
	
	/**
	 * Scrape main page
	 * @param Client $client
	 * @return static
	 */
	public static function scrape(Client $client)
	{
		$crawler = $client->request('GET', static::getUrl());
		
		return new static($client, $crawler);
	}
}