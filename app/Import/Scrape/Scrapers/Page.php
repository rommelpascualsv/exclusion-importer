<?php

namespace App\Import\Scrape\Scrapers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Page
{	
	/**
	 * @var Client
	 */
	protected $client;
	
	/**
	 *
	 * @var Crawler
	 */
	protected $crawler;
	
	/**
	 * Get url
	 * @param array $args
	 * @return string
	 */
	public static function getUrl($args = [])
	{
		if (is_string($args)) {
			$args = [$args];
		}
		
		return vsprintf(static::$url, $args);
	}
	
	/**
	 * Instantiate
	 * @param Client $client
	 * @param Crawler $crawler
	 */
	public function __construct(Client $client, Crawler $crawler = null)
	{
		$this->client = $client;
		$this->crawler = $crawler;
	}
	
	/**
	 * Get client
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}
	
	/**
	 * Get Crawler
	 * @return Crawler
	 */
	public function getCrawler()
	{
		return $this->crawler;
	}
	
	/**
	 * Get nodes by xpath
	 * @param string $xpath
	 * @param string $errorMessage
	 * @param Crawler $crawler
	 * @return Crawler
	 */
	public function getNodesByXpath($xpath, $errorMessage = '', Crawler $crawler = null)
	{
		return $this->getNodes('filterXPath', $xpath, $errorMessage, $crawler);
	}
	
	/**
	 * Get nodes by css selector
	 * @param string $selector
	 * @param string $errorMessage
	 * @param Crawler $crawler
	 */
	public function getNodesByCss($selector, $errorMessage = '', Crawler $crawler = null)
	{
		return $this->getNodes('filter', $selector, $errorMessage, $crawler);
	}
	
	/**
	 * Get content
	 * @return string
	 */
	public function getContent()
	{
		return $this->client->getResponse()->getContent();
	}
	
	/**
	 * Get nodes
	 * @param string $method
	 * @param string $search
	 * @param string $errorMessage
	 * @param Crawler $crawler
	 * @throws \Exception
	 */
	protected function getNodes($method, $search, $errorMessage = '', Crawler $crawler = null)
	{
		if ($crawler == null) {
			$crawler = $this->crawler;
		}
		
		/** @var Crawler $nodes */
		$nodes = call_user_func([$crawler, $method], $search);
		
		if ($nodes->count() == 0) {
			throw new \Exception($errorMessage);
		}
		
		return $nodes;
	}
}