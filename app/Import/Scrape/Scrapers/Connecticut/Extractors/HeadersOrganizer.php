<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Extractors;


use League\Csv\Writer;

class HeadersOrganizer
{
	/**
	 * @var array
	 */
	protected $data;
	
	/**
	 * @var string
	 */
	protected $savePath;
	
	/**
	 * @var array
	 */
	protected $result = [
			['CATEGORY', 'OPTION']
	];
	
	/**
	 * Instantiate
	 * @param array $data
	 * @param string $savePath
	 */
	public function __construct(array $data, $savePath = '')
	{
		$this->data = $data;
		$this->savePath = $savePath;
	}
	
	/**
	 * Get data
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Get save path
	 * @return string
	 */
	public function getSavePath()
	{
		return $this->savePath;
	}
	
	/**
	 * Organize headers
	 * @return $this
	 */
	public function organize()
	{	
		$headersMap = [];
		$headersLastIndex = 0;
		
		foreach ($this->data as $data) {
			/* get category and option */
			$category = array_shift($data);
			$option = array_shift($data);
			
			/* plot matching header indexes */
			$matchingHeaderIndexes = [];
			
			foreach ($data as $header) {
				/* add entry to headers map */
				if (! array_key_exists($header, $headersMap)) {
					$headersMap[$header] = $headersLastIndex;
					$headersLastIndex++;
				}
				
				/* add matching header index */
				$matchingHeaderIndexes[] = $headersMap[$header];
			}
			
			/* plot YES to all matching headers */
			$matchingHeaders = array_fill(0, count($headersMap), '');
			
			foreach ($matchingHeaderIndexes as $index) {
				$matchingHeaders[$index] = 'YES';
			}
			
			$this->addResult($category, $option, $matchingHeaders);
		}
		
		$this->setResultHeader(array_keys($headersMap));
		
		return $this;
	}
	
	/**
	 * Save result to csv
	 */
	public function save()
	{
		$saveDir = dirname($this->savePath);
		
		if (! is_dir($saveDir)) {
			mkdir($saveDir, 0755, true);
		}
		
		$writer = Writer::createFromPath($this->savePath, 'w+');
		$writer->insertAll($this->result);
	}
	
	/**
	 * Get result
	 * @return array
	 */
	public function getResult()
	{
		return $this->result;
	}
	
	/**
	 * Add result
	 * @param string $category
	 * @param string $option
	 * @param array $matchingHeaders
	 */
	protected function addResult($category, $option, array $matchingHeaders)
	{
		$this->result[] = array_merge([$category, $option], $matchingHeaders);
	}
	
	/**
	 * Set result header
	 * @param array $headers
	 */
	protected function setResultHeader(array $headers)
	{
		$this->result[0] = array_merge($this->result[0], $headers);
	}
}