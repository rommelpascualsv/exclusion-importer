<?php

namespace App\Import\Scrape\Data\Extractors;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use Symfony\Component\DomCrawler\Crawler;
use App\Import\Scrape\Components\FilesystemInterface;

class ConnecticutExtractor
{
	/**
	 * @var ConnecticutCrawler
	 */
	protected $crawler;
	
	/**
	 * @var FilesystemInterface
	 */
	protected $filesystem;
	
	/**
	 * Instantiate
	 * @param ConnecticutCrawler $crawler
	 */
	public function __construct(ConnecticutCrawler $crawler, FilesystemInterface $filesystem)
	{
		$this->crawler = $crawler;
		$this->filesystem = $filesystem;
	}
	
	/**
	 * Save extracted categories to a JSON
	 */
	public function extractCategories()
	{
		$mainCrawler = $this->crawler->getMainCrawler();
		$categoryHeadersCrawler = $mainCrawler->filter($this->crawler->getSelector('main', 'category_header'));
		
		$categories = $this->getCategoriesData($categoryHeadersCrawler);
		
		$this->filesystem->put(
				'extracted/connecticut-categories.json',
				json_encode($categories, JSON_PRETTY_PRINT
		));
	}
	
	public function getMainCrawler()
	{
		return $this->crawler->getMainCrawler();
	}
	
	public function getCategoryHeadersCrawler(Crawler $mainCrawler)
	{
		return $mainCrawler->filter($this->crawler->getSelector('main', 'category_header'));
	}
	
	/**
	 * Get category data
	 * @param Crawler $categoryHeaderCrawler
	 * @return array
	 */
	public function getCategoryData(Crawler $categoryHeaderCrawler, $i = 0)
	{
		$name = $this->getCategoryName($categoryHeaderCrawler);
		$options = $this->getCategoryOptions($categoryHeaderCrawler, $i);
		
		return [
				'name' => $name,
				'options' => $options
		];
	}
	
	public function getCategoriesData(Crawler $categoryHeadersCrawler)
	{
		$categories = [];
		
		$categoryHeadersCrawler->each(function(Crawler $categoryHeaderNode, $i) use (&$categories) {
			$data = $this->getCategoryData($categoryHeaderNode, $i);
			$key = $this->getKey($data['name']);
				
			$categories[$key] = $data;
		});
		
		return $categories;
	}
	
	/**
	 * Get category text
	 * @param Crawler $nodeCrawler
	 * @return string
	 */
	public function getCategoryName(Crawler $nodeCrawler)
	{
		$text = trim(
				str_replace(
						[
								'-  (click this bar to expand/collapse group)',
								"\xA0",
								"\xC2"
						],
						'',
						$nodeCrawler->text()
				)
		);
		
		return $text;
	}
	
	/**
	 * Get category options
	 * @param Crawler $nodeCrawler
	 * @return array
	 */
	public function getCategoryOptions(Crawler $nodeCrawler, $i = 0)
	{
		$collapsePanelCrawler = $nodeCrawler->nextAll()->first();
		$options = [];
		
		$collapsePanelCrawler->filter('.collapsePanel > table')->each(function(Crawler $crawler) use (&$options, $i) {
			$data = $this->getOptionData($crawler, $i);
			
			$key = $this->getKey($data['name']);
			$options[$key] = $data;
		});
		
		return $options;
	}
	
	/**
	 * Get options data
	 * @param Crawler $optionsTableNode
	 * @return array
	 */
	public function getOptionData(Crawler $optionsTableNode, $index = 0)
	{
		$nameColumnNode = $optionsTableNode->filter('td:nth-child(2)');	
		
		if ($nameColumnNode->count() == 0) {
			throw new \Exception('Table name column cannot be found on node ' . $index . '.');
		}
		
		$name = $this->getOptionName($nameColumnNode->text());
		
		
		$checkboxNode = $optionsTableNode->filter('td:nth-child(1) input[type=checkbox]');
		
		if ($checkboxNode->count() == 0) {
			throw new \Exception(sprintf('Option name checkbox cannot be found on node %d', $index));
		}
		
		$fieldName = $checkboxNode->attr('name');
		
		return [
				'name' => $name,
				'field_name' => $fieldName
		];
	}
	
	/**
	 * Get key
	 * @param string $name
	 * @return string
	 */
	protected function getKey($name)
	{
		return strtolower(preg_replace(
				['/[^a-zA-Z0-9 ]/', '/ /', '/__/'], 
				['', '_', '_'], 
				$name
		));
	}
	
	/**
	 * Get option name
	 * @param string $text
	 * @return string
	 */
	protected function getOptionName($text)
	{
		return $this->stripNodeText('(No Fee Required)', $text);
	}
	
	/**
	 * Strip node text
	 * @param string $needle
	 * @param string $haystack
	 * @return string
	 */
	protected function stripNodeText($needle, $haystack)
	{
		return trim(str_replace([$needle, "\xA0", "\xC2"], '', $haystack));
	}
}