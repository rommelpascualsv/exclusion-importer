<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Page;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use App\Exceptions\Scrape\ScrapeException;

class MainPage extends Page
{	
	/*
	 * @var string
	 */
	protected static $url = 'https://www.elicense.ct.gov/Lookup/GenerateRoster.aspx';
	
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
	
	/**
	 * Tick options
	 * @param OptionCollection $options
	 * @throws ScrapeException
	 * @return \Symfony\Component\DomCrawler\Form
	 */
	public function tickOptions(OptionCollection $options)
	{
		$buttonNode = $this->getNodesByCss(
				'input[type="submit"][name="ctl00$MainContentPlaceHolder$btnRosterContinue"]',
				'Main page submit button cannot be found'
		);
		$form = $buttonNode->form();
		
		foreach ($options as $option) {
			$fieldName = $option->getFieldName();
			
			if (empty($form[$fieldName])) {
				throw new ScrapeException($option->getName() . ' option cannot be found');
			}
			
			$form[$fieldName]->tick();
		}
		
		return $form;
	}
}