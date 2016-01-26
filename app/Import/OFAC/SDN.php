<?php namespace App\Import\OFAC;

class SDN {

	/**
	 * OFAC SDN source data (XML)
	 *
	 * @var		string		$sourceFile
	 * @access	protected
	 */
	protected $sourceFile = 'http://www.treasury.gov/ofac/downloads/sdn.xml';

	/**
	 * A complete multi-dimensional array of the OFAC SDN data
	 *
	 * @var		array		$arrayEntriesData
	 * @access	protected
	 */
	protected $arrayEntriesData;

	/**
	 * An array of the file's publish data
	 *
	 * @var		array		$arrayPublishData
	 */
	protected $arrayPublishData;

	/**
	 * Index of the Publish Information within the SDN data set
	 *
	 * @var		string		$publishDataKey
	 */
	protected $publishDataKey = 'publshInformation';

	/**
	 * Index of main entries within the SDN data set.
	 *
	 * @var		string		$entriesKey
	 */
	protected $entriesKey = 'sdnEntry';

	/**
	 * An array of normalized data sub-sets
	 *
	 * @var		array		$normalizedData
	 */
	protected $normalizedData = [
		'sdn_entries'				=> [],
		'sdn_program_list'			=> [],
		'sdn_id_list'				=> [],
		'sdn_aka_list'				=> [],
		'sdn_address_list'			=> [],
		'sdn_nationality_list'		=> [],
		'sdn_citizenship_list'		=> [],
		'sdn_date_of_birth_list'	=> [],
		'sdn_place_of_birth_list'	=> [],
		'sdn_vessel_info'			=> [],
	];

	/**
	 * Constructor
	 * 	- Set Source File Path
	 * 	- Load Source File
	 * 	- Normalize data
	 *
	 * @var		string		$sourceFile	-	An alternative XML data source
	 */
	public function __construct($sourceFile = NULL )
	{
		// Load the data
		$this->loadXmlFile();

		// Normalize the data into different data sets per table
		$this->normalizeData();
	}

	/**
	 * Load the XML source data -> parse into an array -> set _array_data property
	 */
	protected function loadXmlFile()
	{
		// Load the XML file. This returns a SimpleXMLElement object
		$xmlObjectData = simplexml_load_file($this->sourceFile);

		// Decode the json data for a clean multi-dimensional array
		$arrayData = json_decode(json_encode($xmlObjectData), true);

		// Assign the publish data to its respective property
		$this->arrayPublishData = $arrayData[$this->publishDataKey];

		// Assign the entries to its respective property
		$this->arrayEntriesData = $arrayData[$this->entriesKey];
	}

	/**
	 * Getter for the path to the XML data source file.
	 *
	 * @return	string
	 */
	public function getSourceFile()
	{
		return $this->sourceFile;
	}

	/**
	 * Getter for _array_data property
	 *
	 * @return	array
	 */
	public function getEntriesArray()
	{
		return $this->arrayEntriesData;
	}

	/**
	 * Getter for total number of entries
	 *
	 * @return	int
	 */
	public function getTotalEntries()
	{
		return count($this->arrayEntriesData);
	}

	/**
	 * Getter for the publish info
	 *
	 * @return array
	 */
	public function getPublishData()
	{
		return $this->arrayPublishData;
	}

	/**
	 * Getter for normalized data array. Pass a key to show just one set
	 *
	 * @param		string	$key
	 * @return		array
	 */
	public function getNormalizedData($key = NULL )
	{
		// If no key was passed or the passed key is not valid...
		if ($key === NULL OR !array_key_exists($key, $this->normalizedData))
		{
			// return all normalized data
			return $this->normalizedData;
		}

		// Return only the data with the passed key
		return $this->normalizedData[$key];
	}

	protected function addToNormalizedData(array $parsedSection, $table )
	{
		if ( ! $this->isValidSdnTable($table) ) return;

		foreach ($parsedSection as $section )
		{
			$this->normalizedData[$table][] = $section;
		}
	}

	/**
	 * - Normalize the SDN data set into separate data sets per table.
	 * - We loop through all nested data sets and create a sub-set from that attribute.
	 * - Each sub-set contains a sdn_entry_id key.
	 * - Sub-sets are assigned to the key that corresponds to the table they will be stored in within
	 *   the _normalized_data property
	 */
	protected function normalizeData()
	{
		// Open a loop for all entries
		foreach ($this->arrayEntriesData as $key => $sdnEntry )
		{
			// Save the sdn_entry_id
			$sdnEntryId = $sdnEntry['uid'];

			// Parse the main SDN entries
			$entry = [
				'uid'		=> isset($sdnEntry['uid']) ? $sdnEntry['uid'] : '',
				'firstName'	=> isset($sdnEntry['firstName']) ? $sdnEntry['firstName'] : '',
				'lastName'	=> isset($sdnEntry['lastName']) ? $sdnEntry['lastName'] : '',
				'title'		=> isset($sdnEntry['title']) ? $sdnEntry['title'] : '',
				'sdnType'	=> isset($sdnEntry['sdnType']) ? $sdnEntry['sdnType'] : '',
				'remarks'	=> isset($sdnEntry['remarks']) ? $sdnEntry['remarks'] : '',
			];

			// Add the main SDN Entry
			$this->normalizedData['sdn_entries'][] = $entry;


			// $programList = $this->_parse_section($sdn_entry['programList'], 'program', $sdnEntryId);
			// $this->_save('sdn_program_list', $programList);

			// We will parse all known nested data sets

			// Parse ProgramList
			if ( isset($sdnEntry['programList']) )
			{
				$programLists = $this->parseSection($sdnEntry['programList'], 'program', $sdnEntryId);
				$this->addToNormalizedData($programLists, 'sdn_program_list');
			}

			// Parse idList
			if ( isset($sdnEntry['idList']) )
			{
				$idLists = $this->parseSection($sdnEntry['idList'], 'id', $sdnEntryId);
				$this->addToNormalizedData($idLists, 'sdn_id_list');
			}

			// Parse akaList
			if ( isset($sdnEntry['akaList']) )
			{
				$akaLists = $this->parseSection($sdnEntry['akaList'], 'aka', $sdnEntryId);
				$this->addToNormalizedData($akaLists, 'sdn_aka_list');
			}

			// Parse addressList
			if ( isset($sdnEntry['addressList']) )
			{
				$addressLists = $this->parseSection($sdnEntry['addressList'], 'address', $sdnEntryId);
				$this->addToNormalizedData($addressLists, 'sdn_address_list');
			}

			// parse nationalityList
			if ( isset($sdnEntry['nationalityList']) )
			{
				$nationalityLists= $this->parseSection($sdnEntry['nationalityList'], 'nationality', $sdnEntryId);
				$this->addToNormalizedData($nationalityLists, 'sdn_nationality_list');
			}

			// Parse citizenShipList
			if ( isset($sdnEntry['citizenshipList']) )
			{
				$citizenshipList = $this->parseSection($sdnEntry['citizenshipList'], 'citizenship', $sdnEntryId);
				$this->addToNormalizedData($citizenshipList, 'sdn_citizenship_list');
			}

			// Parse dateOfBirthList
			if ( isset($sdnEntry['dateOfBirthList']) )
			{
				$dateOfBirthList = $this->parseSection($sdnEntry['dateOfBirthList'], 'dateOfBirthItem', $sdnEntryId);
				$this->addToNormalizedData($dateOfBirthList, 'sdn_date_of_birth_list');
			}

			// Parse placeOfBirthList
			if ( isset($sdnEntry['placeOfBirthList']) )
			{
				$placeOfBirthList = $this->parseSection($sdnEntry['placeOfBirthList'], 'placeOfBirthItem', $sdnEntryId);
				$this->addToNormalizedData($placeOfBirthList, 'sdn_place_of_birth_list');
			}

			// Parse vessel info
			if ( isset($sdnEntry['vesselInfo']) )
			{
				$info = isset($this->parseVesselInfo($sdnEntry['vesselInfo'], $sdnEntryId)[0]) ? $this->parseVesselInfo($sdnEntry['vesselInfo'], $sdnEntryId)[0] : null;
				$this->normalizedData['sdn_vessel_info'][] = $info;
			}

		} // End loop

	}

	/**
	 * The vesselInfo is not consistent with the rest of the nested data sets. A separate method is therefore needed
	 * to parse it into a subset.
	 *
	 * @param	array	$data
	 * @param	int		$mainId
	 * @return 	array
	 */
	protected function parseVesselInfo($data, $mainId )
	{
		// Assign an sdn_entry_id to the passed set
		$data['sdn_entry_id'] = $mainId;

		// Return the data
		return [$data];
	}

	/**
	 * Parse a nested data set into a sub-set
	 *
	 * @param	array	$section
	 * @param	string	$mainKey
	 * @param	int		$mainId
	 * @return	array
	 */
	protected function parseSection($section, $mainKey, $mainId )
	{
		// Initialize the main array
		$mainArray = [];

		// In order to accommodate all formats of nested data sets (section), we do a few check
		// to find a matching scenario.

		if ( is_array($section[$mainKey]) )			// The passed section is an array
		{
			if ( isset($section[$mainKey][0]) )			// There is a 0th index would indicate more than one array in the set+
			{
				// Open a loop of all arrays in the set
				foreach ($section[$mainKey] as $row )
				{
					if ( is_array($row) )					// The value of this set is an array
					{
						// Add the sdn_entry_id to the rowe
						$row['sdn_entry_id'] = $mainId;

						// Add the row to the main array
						$mainArray[] = $row;
					}
					else									// The value of this set is a string|int|bool|etc (not array)
					{
						// Init a new row array
						$newRow = [];

						// Add the snd_entry_id to the new row
						$newRow['sdn_entry_id'] = $mainId;

						// Add the rest of the rows indices to the new row
						$newRow[$mainKey] = $row;

						// Add the new row to the main array
						$mainArray[] = $newRow;
					}
				}
			}
			else											// No 0th element exists so only one array in the set
			{
				// Add the sdn_entry_id to the one array in the section
				$section[$mainKey]['sdn_entry_id'] = $mainId;

				// Add the one array in the set to the main array
				$mainArray[] = $section[$mainKey];
			}
		}
		else											// The passed section is not an array
		{
			/*
			// Init a row array
			$row = [];

			// Add the sdn_entry_id to the row
			$row['sdn_entry_id'] = $main_id;

			// Add the singular value in the passed section to the row
			$row[$main_key] = $section[$main_key];
			*/



			$row = $section;
			$row['sdn_entry_id'] = $mainId;

			// Add the row to the main array
			$mainArray[] = $row;
		}

		// return the main array
		return $mainArray;
	}

	/**
	 * Save normalized data to their respective tables. Pass a key that exists in the _normalized_data_array array
	 * to only save one sub-set.
	 *
	 * @param	string		$key
	 */
	public function saveToDatabase($key = NULL )
	{
		// If no key is passed or the passed key is not valid...
		if ( $key === NULL OR ! array_key_exists($key, $this->normalizedData) )
		{
			// Iterate over _normalized_data_array and save all
			foreach ($this->normalizedData as $key => $value )
			{
				// Save the current sub-set
				$this->save($key, $value);
			}
		}
		else // A valid key was passed
		{
			// Save only that index in the _normalized_data_array array
			$this->save($key, $this->normalizedData[$key]);
		}
	}

	/**
	 * Save a sub-set to the database. The query uses REPLACE to ensure the the UNIQUE keys are not duplicated.
	 *
	 * @param	string	$table
	 * @param	array	$data
	 * @return	int|mixed
	 */
	protected function save($table, $data )
	{
		if ( PHP_SAPI === 'cli' )
		{
			echo "| - Saving table: {$table} ................ ";
		}

		foreach ( $data as $row )
		{
			$sql = "REPLACE INTO `%s` ( `%s` ) VALUES ( %s )";

			foreach ( $row as $key => &$column )
			{
				if ( $key == 'mainEntry' )
				{
					$column = ( $column === 'true' ) ? 1 : 0 ;
				}
				else
				{
					$column = app('db')->connection()->getPdo()->quote($column);
				}
			}


			$keys = join("`, `", array_keys($row));

			$vals = join(", ", array_values($row));

			$query = sprintf($sql, $table, $keys, $vals);

			app('db')->statement($query);
		}

		if ( PHP_SAPI === 'cli' )
		{
			echo " Saved!\n";
		}

		// echo '<pre>';var_dump( $data );echo '</pre>';
	}

	/**
	 * Truncate all database tables storing SDN data
	 */
	public function truncateDatabase()
	{
		foreach ($this->normalizedData as $key => $value )
		{
			app('db')->table($key)->truncate();
		}
	}

	public function isValidSdnTable($tableName )
	{
		$validTableNames = array_keys($this->normalizedData);

		return in_array($tableName, $validTableNames);
	}

	public function getValidSdnTables()
	{
		return array_keys($this->normalizedData);
	}

}