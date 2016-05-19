<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use League\Csv\Reader;
use App\Import\Scrape\ProgressTrackers\TracksProgress;

class CsvImporter
{

    use TracksProgress;

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
     * 
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
        $this->trackProgress('Importing data from ' . count($this->importData) . ' csv files...');
        
		$timestamp = Carbon::now()->format('Y-m-d H:i:s');
		
		foreach ($this->importData as $data) {
			$this->importCsv($data, $timestamp);
		}
	}
	
	/**
	 * Extract rows from a csv file and store corresponding rosters in the db
     * 
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
		
		$mapper = MapperFactory::createByKeys($data['category'], $data['option'], $results[0]);
		$licenseTypeId = $this->dbFindLicenseTypeIdByKey($data['category'] . '.' . $data['option']);
		
		for ($i = 1; $i < $resultsCount; $i++) {
		    $row = $results[$i];
		    $csvData = $mapper->getCsvData($row);
		    $dbData = $mapper->getDbData($csvData);
		    
		    $this->dbInsertRoster($licenseTypeId, $dbData, $timestamp);
		}
        
        $this->trackInfoProgress(
            'Imported ' . ($resultsCount - 1) . ' record(s) from ' . $data['file_path']
        );
	}
	
	/**
	 * Find license type id by key
     * 
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
     * 
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
}