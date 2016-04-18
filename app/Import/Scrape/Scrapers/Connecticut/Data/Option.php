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
	 * @var string
	 */
	protected $category;
	
	/**
	 * Instantiate
	 * @param string $name
	 * @param string $fieldName
	 * @param string $fileName
	 * @param string $category
	 */
	public function __construct($name, $fieldName, $fileName, $category)
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
}