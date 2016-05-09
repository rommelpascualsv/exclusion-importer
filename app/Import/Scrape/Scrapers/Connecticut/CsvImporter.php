<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use Illuminate\Database\ConnectionInterface;
use League\Csv\Reader;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Carbon\Carbon;

class CsvImporter
{
	const TYPE_FACILITY = 'facility';
	const TYPE_PERSON = 'person';
	/**
	 * @var array
	 */
	protected $importData;
	
	/**
	 * @var ConnectionInterface
	 */
	protected $db;
	
	/**
	 * Initialize
	 * @param array $importData
	 * @param ConnectionInterface $db
	 */
	public function __construct(array $importData, ConnectionInterface $db)
	{
		$this->importData = $importData;
		$this->db = $db;
	}
	
	/**
	 * Import csv files to db
	 */
	public function import()
	{
		$timestamp = Carbon::now()->format('Y-m-d H:i:s');
		
		foreach ($this->importData as $data) {
			$this->importCsv($data, $timestamp);
		}
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
		$licenseTypeId = $this->dbFindLicenseTypeIdByKey($data['option']);
		
		
		for ($i = 1; $i < $resultsCount; $i++) {
		    $row = $results[$i];
		    $csvData = $mapper->getCsvData($row);
		    $dbData = $mapper->getDbData($csvData);
		    
		    $this->dbInsertRoster($licenseTypeId, $dbData, $timestamp);
		}
	}
	
	/**
	 * Find license type id by key
	 * @param string $key
	 * @return int|null
	 */
	public function dbFindLicenseTypeIdByKey($key)
	{
	    $licenseType = $this->db->table('ct_license_types')
	    ->select('id')
	    ->where('key', '=', $key)
	    ->first();
	
	    return (is_object($licenseType)) ? $licenseType->id : null;
	}
	
	/**
	 * Insert roster to database
	 * @param int $licenseTypeId
	 * @param array $data
	 * @param string $timestamp
	 */
    public function dbInsertRoster($licenseTypeId, $data, $timestamp)
    {
        $data = array_merge([
            'ct_license_types_id' => $licenseTypeId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ], $data);
        
        $this->db->table('ct_rosters')->insert($data);
	}
	
	
	public function dbInsertFacilityRoster($optionId, array $data, $timestamp)
	{
		$facilityId = $this->dbFindFacilityIdByName($data['name']);
		
		if (! $facilityId) {
			$facilityId = $this->dbInsertFacility($data['name'], $timestamp);
		}
		
		$this->dbInsertRoster($optionId, $facilityId, null, $data, $timestamp);
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
	 * Insert person roster to db
	 * @param int $optionId
	 * @param array $data
	 * @param string $timestamp
	 */
	public function dbInsertPersonRoster($optionId, array $data, $timestamp)
	{
		$personId = $this->dbFindPersonIdByName($data['first_name'], $data['last_name']);
		
		if (! $personId) {
			$personId = $this->dbInsertPerson($data['first_name'], $data['last_name'], $timestamp);
		}
		
		$this->dbInsertRoster(
				$optionId,
				null,
				$personId,
				$data,
				$timestamp
		);
	}
	
	/**
	 * Insert person to db
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $timestamp
	 */
	public function dbInsertPerson($firstName, $lastName, $timestamp)
	{
		$personId = $this->db->table('ct_roster_people')->insertGetId([
				'first_name' => $firstName,
				'last_name' => $lastName,
				'created_at' => $timestamp,
				'updated_at' => $timestamp
		]);
		
		return $personId;
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
	 * Find person id by first name and last name
	 * @param string $firstName
	 * @param string $lastName
	 * @return int|null
	 */
	public function dbFindPersonIdByName($firstName, $lastName)
	{
		$person = $this->db->table('ct_roster_people')
			->select('id')
			->where('first_name', '=', $firstName)
			->where('last_name', '=', $lastName)
			->first();
		
		$personId = (is_object($person)) ? $person->id : null;
		
		return $personId;
	}
}