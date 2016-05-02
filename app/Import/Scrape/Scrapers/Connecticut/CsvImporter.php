<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use Illuminate\Database\ConnectionInterface;
use League\Csv\Reader;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;

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
	
	/**
	 * Extract rows from a csv file and store corresponding rosters in the db
	 * @param array $data
	 * @param string $timestamp
	 */
	public function importCsv(array $data, $timestamp)
	{
		$reader = Reader::createFromPath($data['file_path']);
		$results = $reader->fetchAll();
		$resultsCount = count($results);
		
		if ($resultsCount < 2) {
			//@todo: handle no records here
			return;
		}
		
		$mapper = MapperFactory::createByKeys($data['category'], $data['option']);
		$optionId = $this->dbFindOptionIdByKey($data['option']);
		
		for ($i = 1; $i < $resultsCount; $i++) {
			$row = $results[$i];
			$csvData = $mapper->getCsvData($row);
			$dbData = $mapper->getDbData($csvData);
			
			$this->dbInsertRoster($dbData, $optionId, $timestamp);
		}
	}
	
	/**
	 * Insert facility to database
	 * @param string $name
	 * @param string $timestamp
	 */
	public function dbInsertFacility($name, $timestamp)
	{		
		$id = $this->db->table('ct_roster_facilities')->insertGetId([
				'name' => $name,
				'created_at' => $timestamp,
				'updated_at' => $timestamp
		]);
		
		return $id;
	}
	
	/**
	 * Find facility
	 * @param string $name
	 * @return int|null
	 */
	public function dbFindFacilityIdByName($name)
	{
		$facility = $this->db->table('ct_roster_facilities')
		->select('id')
		->where('name', '=', $name)
		->first();
	
		$facilityId = (is_object($facility)) ? $facility->id : null;
	
		return $facilityId;
	}
	
	/**
	 * Insert roster to database
	 * @param array $data
	 * @param int $optionId
	 * @param string $timestamp
	 */
	public function dbInsertRoster(array $data, $optionId, $timestamp)
	{
		// get facility id
		$facilityId = $this->dbFindFacilityIdByName($data['name']);
		
		if (! $facilityId) {
			$facilityId = $this->dbInsertFacility($data['name'], $timestamp);
		}
		
		$this->db->table('ct_rosters')->insert([
				'option_id' => $optionId,
				'facility_id' => $facilityId,
				'address1' => $data['address1'],
				'address2' => $data['address2'],
				'city' => $data['city'],
				'county' => $data['county'],
				'state_code' => $data['state_code'],
				'zip' => $data['zip'],
				'complete_address' => $data['complete_address'],
				'created_at' => $timestamp,
				'updated_at' => $timestamp
		]);
	}
	
	/**
	 * Find option id by key
	 * @param string $key
	 * @return int|null
	 */
	public function dbFindOptionIdByKey($key)
	{
		$option = $this->db->table('ct_roster_options')
			->select('id')
			->where('key', '=', $key)
			->first();
		
		$optionId = (is_object($option)) ? $option->id : null;
		
		return $optionId;
	}
}