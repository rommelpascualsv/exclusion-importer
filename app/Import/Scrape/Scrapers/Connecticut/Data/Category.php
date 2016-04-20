<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data;

class Category
{	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $dir;
	
	/**
	 * Instantiate
	 * @param string $name
	 * @param string $dir
	 */
	public function __construct($name, $dir)
	{
		$this->name = $name;
		$this->dir = $dir;
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
	 * Get dir
	 * @return string
	 */
	public function getDir()
	{
		return $this->dir;
	}
}