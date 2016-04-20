<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data;

class Option
{
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $fieldName;
	
	/**
	 * @var string
	 */
	protected $fileName;
	
	/**
	 * @var Category
	 */
	protected $category;
	
	/**
	 * Instantiate
	 * @param string $name
	 * @param string $fieldName
	 * @param string $fileName
	 * @param Category $category
	 */
	public function __construct($name, $fieldName, $fileName, Category $category)
	{
		$this->name = $name;
		$this->fieldName = $fieldName;
		$this->fileName = $fileName;
		$this->category = $category;
	}
	
	/**
	 * Get name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Get field name
	 * @return string
	 */
	public function getFieldName()
	{
		return $this->fieldName;
	}
	
	/**
	 * Get file name
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}
	
	/**
	 * Get category
	 * @return Category
	 */
	public function getCategory()
	{
		return $this->category;
	}
	
	/**
	 * Get descriptive name
	 * @return string
	 */
	public function getDescriptiveName()
	{
		return '['. $this->category->getName() .'] ' . $this->name;
	}
}