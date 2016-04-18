<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data;

class CategoryCollection
{
	/**
	 * @var array
	 */
	protected $data = [];
	
	/**
	 * Instantiate
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}
	
	/**
	 * Get options
	 * @param array $args
	 * @return Option[]
	 */
	public function getOptions(array $args)
	{
		$options = [];
		
		foreach ($args as $key => $value) {
			if (is_string($value)) {
				$newOptions = $this->getAllOptions($value);
			} elseif (is_array($value)) {
				$newOptions = $this->getFilteredOptions($key, $value);
			}
			
			$options = array_merge($options, $newOptions);
		}
		
		return new OptionCollection($options);
	}
	
	/**
	 * Get all options
	 * @param string $categoryKey
	 * @return Option[]
	 */
	public function getAllOptions($categoryKey)
	{
		$options = [];
	
		foreach ($this->getCategoryOptionsData($categoryKey) as $data) {
			$options[] = $this->getOption($data, $categoryKey);
		}
	
		return $options;
	}
	
	/**
	 * Get category options data
	 * @param string $key
	 * @return array
	 */
	public function getCategoryOptionsData($key)
	{
		return $this->data[$key]['options'];
	}
	
	/**
	 * Get filtered options
	 * @param string $categoryKey
	 * @param string $optionKeys
	 * @return Option[]
	 */
	public function getFilteredOptions($categoryKey, $optionKeys)
	{
		$options = [];
		$optionsData = $this->getCategoryOptionsData($categoryKey);
		
		foreach ($optionKeys as $key) {
			$options[] = $this->getOption($optionsData[$key], $categoryKey);
		}
		
		return $options;
	}
	
	/**
	 * Get option
	 * @param array $data
	 * @param string $category
	 * @return Option
	 */
	public function getOption(array $data, $category)
	{
		return new Option(
				$data['name'],
				$data['field_name'],
				$data['file_name'],
				$category
		);	
	}
}