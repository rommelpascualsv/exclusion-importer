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
		return $this->getFilteredOptions(
				$categoryKey,
				array_keys($this->data[$categoryKey]['options'])
		);
	}
	
	/**
	 * Get filtered options
	 * @param string $categoryKey
	 * @param array $optionKeys
	 * @return Option[]
	 */
	public function getFilteredOptions($categoryKey, array $optionKeys)
	{
		$categoryData = $this->data[$categoryKey];
		$category = new Category($categoryData['name'], $categoryKey);
		$options = [];
		
		foreach ($optionKeys as $key) {
			$data = $categoryData['options'][$key];
			
			$options[] = new Option(
				$data['name'],
				$data['field_name'],
				$data['file_name'],
				$category
			);
		}
		
		return $options;
	}
}