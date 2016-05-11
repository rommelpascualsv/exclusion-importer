<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Page;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use App\Exceptions\Scrape\ScrapeException;
use Symfony\Component\DomCrawler\Crawler;

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
	
	/**
	 * Get panel nodes
	 * @return Crawler
	 */
	public function getPanelNodes()
	{
	    return $this->getNodesByCss('.panel', 'Panels cannot be found');
	}
	
	/**
	 * Get category text
	 * @param Crawler $panelNode
	 * @param int $i
	 * @return string
	 */
	public function getCategoryText(Crawler $panelNode, $i)
	{
	    $panelHeadingNode = $this->getNodesByCss(
	        '.panel-heading',
	        'Panel heading cannot be found on node ' . $i,
	        $panelNode
        );
	     
	    return $this->getTrimmedNodeText($panelHeadingNode);
	}
	
	/**
	 * Get option names
	 * @param Crawler $panelNode
	 * @param int $i
	 * @throws ScrapeException
	 * @return array
	 */
	public function getOptionsData(Crawler $panelNode, $i)
	{
	    // get all checkbox names
	    $checkboxes = $this->getNodesByCss(
	        '.panel-body > span > input[type=checkbox]', 
	        'Panel checkboxes cannot be found on node ' . $i,
	        $panelNode
        );
	    
	    $checkboxNames = [];
	    
	    $checkboxes->each(function(Crawler $node) use (&$checkboxNames) {
	        $checkboxNames[] = $node->attr('name');
	    });
	    
	    // get option data
	    $panelBodyHtml = $this->getNodesByCss('.panel-body', '', $panelNode)->html();
	    $data = [];
	    
	    foreach ($checkboxNames as $index => $fieldName) {
	        // get option name
	        $escapedFieldName = preg_quote($fieldName, '/');
	        $regex = '/' . $escapedFieldName . '"><\/span>([^\xA0]+)/';
	        
	        if ( ! preg_match($regex, $panelBodyHtml, $matches)) {
	            throw new ScrapeException('Option name match cannot be found for ' . $fieldName);
	        }
	        
	        $optionName = $this->getTrimmedText($matches[1], '(No Fee Required)');
	        
	        // push option data
	        $data[] = [
	            'name' => $optionName,
	            'field_name' => $fieldName
	        ];
	    }
	    
	    return $data;
	}
}