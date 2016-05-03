<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers;

interface MapperInterface
{
	/**
	 * Get csv data
	 * @param array $data
	 * @return array
	 */
	public function getCsvData(array $data);
	
	/**
	 * Get db data
	 * @param array $data
	 * @return array
	 */
	public function getDbData(array $data);
}