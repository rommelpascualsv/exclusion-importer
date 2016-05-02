<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use Illuminate\Database\ConnectionInterface;

class CsvImporter
{
	/**
	 * @var ConnectionInterface
	 */
	protected $db;
	
	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
	}
	
	public function import()
	{
	}
	
	public function dbInsertFacility($name, $timestamp)
	{		
		$this->db->table('ct_roster_facilities')->insert([
				'name' => $name,
				'created_at' => $timestamp,
				'updated_at' => $timestamp
		]);
	}
}