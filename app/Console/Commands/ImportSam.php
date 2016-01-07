<?php defined('SYSPATH') OR die('No Direct Script Access');

use Guzzle\Http\Client as HTTPClient;
use SLV\Common\File;
use Stream\Entity\SamHash;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class Task_ImportSam extends Minion_Task {

    use Trait_Log;
    protected static $columnMappings = [
        'State / Province' => 'State',
        'Zip Code' => 'Zip',
    ];
    /**
     * @var	array	$_options
     */
    protected $_options = [
        ['url' => null],
    ];
    protected $toCreate = [];
    protected $columnsToImport;


    /**
     * @var	League\Flysystem\filesystem	$filesystem
     */
    private $filesystem;


    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem(new LocalAdapter(DATAPATH));
        $this->columnsToImport = $this->getDBColumns();

        ini_set('memory_limit', '2048M');

    }

    public function build_validation(Validation $validation)
    {
        return parent::build_validation($validation)
            ->rule('url', 'not_empty');
    }

    private function prepareTempTable()
    {
        $this->logCli('Preparing temp table...');

        $tableName = 'sam_records';
        $tempTableName = 'sam_records_temp';

        DB::query(Database::DELETE, "DROP TABLE IF EXISTS $tempTableName")->execute('exclusion_lists_staging');
        DB::query(Database::INSERT, "CREATE TABLE IF NOT EXISTS $tempTableName LIKE $tableName")->execute('exclusion_lists_staging');
        DB::query(Database::INSERT, "INSERT INTO $tempTableName SELECT * FROM $tableName")->execute('exclusion_lists_staging');
        $this->logCli('temp table ready!');
    }

    protected function _execute(array $params)
    {
        $parsedUrl = parse_url($params['url']);
        $filepath = tempnam(DATAPATH . 'temp', 'samdb');

        if (! $this->getFileFromSam($parsedUrl, $filepath)) {
            $this->logCli('Could not retrieve file from ' . $filepath);
            die;
        }
        $this->logCli('Put ZIP file in ' . $filepath);

        if (! $unzippedFileDir = $this->unzipFile($filepath)) {
            die;
        }

        $unzippedFilepath = $unzippedFileDir . str_ireplace('.ZIP', '.CSV', basename($parsedUrl['path']));

        $this->logCli('Put UnZIPed file in ' . $unzippedFilepath);

        $this->prepareTempTable();

        $file = new File($unzippedFilepath);
        $file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(',', "\"", "|");

        $this->logCli('Getting all current records...');
        $currentRecords = $this->getCurrentRecords();
        $this->logCli('Finished getting all current records');

        $columnsInFile = $this->mapColumns($file->fgetcsv());
        $this->logCli('Importing columns ' . implode(',', $columnsInFile));

        $totalLines = $file->getTotalLines();
        $this->logCli('Total lines in file: ' . $totalLines);

        $activeRecordHashes = [];
		$updated = 0;
        $skipped = 0;
        $total = 0;
        // Iterate csv
        foreach ($file->csvlineIterator($totalLines) as $row)
        {
            if (empty($row)) {
                break;
            }

            $rowData = array_combine($columnsInFile, array_intersect_key($row, $columnsInFile));
            $rowData['Record_Status'] = 1; // Set Record_Status to active since it exists in the file

            try {
                $newHash = new SamHash($rowData);
            } catch (\InvalidArgumentException $e) {
                $this->logCli('An error occurred at row ' . $file->key() . ': ' . $e->getMessage());
                continue;
            }

            $unixActiveDate = strtotime($rowData['Active_Date']);

            $rowData['Active_Date'] = ($unixActiveDate)
                ? strftime('%Y-%m-%d', $unixActiveDate)
                : NULL;

            if (! array_key_exists(strtoupper($newHash), $currentRecords))
            {
                $rowData['hash'] = DB::expr("UNHEX('{$newHash}')");
                $rowData['new_hash'] = DB::expr("UNHEX('{$newHash}')");
                $this->toCreate[] = $rowData;
            }
            else
            {
				$activeRecordHashes[] = strtoupper($newHash);
				if (! $rowData == $currentRecords[strtoupper($newHash)]) {
                    $affectedRows = $this->updateRecords($rowData, $newHash);
                    $updated += $affectedRows;
                } else {
                    $skipped++;
                }
            }
            $total = ($toCreate = count($this->toCreate)) + $updated + $skipped;
            if ($total % 1000 === 0) {
                $statsPattern = 'Total processed: %d -- Total to Create: %d -- Total Updated: %d -- Total Skipped: %d';
                $this->logCli(sprintf($statsPattern, $total, $toCreate, $updated, $skipped));
            }
        }

		$this->logCli('Creating new records...');
        $this->createNewRecords($this->toCreate);
        $this->logCli('Finished creating new records');

		$toDeactivate = $this->getRecordsToDeactivate($currentRecords, $activeRecordHashes);
		$this->logCli(count($toDeactivate) . ' Total to Deactivate.');
		$this->logCli('Deactivating...');
		$deactivated = $this->deactivate($toDeactivate);
		$this->logCli('Finished deactivating');

        $this->logCli('===================================================');
        $this->logCli(count($this->toCreate) . ' Records Created');
        $this->logCli($updated . ' Records Updated');
        $this->logCli($skipped . ' Records Skipped');
        $this->logCli($total . ' Total Processed From New File');
        $this->logCli($deactivated . ' Total Records Deactivated');
        $this->logCli('DONE!');
        $this->logCli('===================================================');
    }

    private function getDBColumns()
    {
        return array_column(DB::query(Database::SELECT, "SHOW COLUMNS FROM sam_records")->execute('exclusion_lists_staging')->as_array(), 'Field');
    }

    private function getCurrentRecords()
    {
        return DB::select('*')
                 ->select(DB::expr('HEX(new_hash) as hex_new_hash'))
                 ->from('sam_records')
                 ->execute('exclusion_lists_staging')
                 ->as_array('hex_new_hash');
    }

    private function createNewRecords($toInsert)
    {
        Database::instance('exclusion_lists_staging')->begin();
        foreach ($toInsert as $record) {
            $columns = array_keys($record);
            $values = array_values($record);
            $this->insertRecord($columns, $values);
        }
        Database::instance('exclusion_lists_staging')->commit();
    }

    /**
     * @param $columns
     * @param $values
     * @throws Kohana_Exception
     */
    protected function insertRecord($columns, $values)
    {
        DB::insert('sam_records_temp')
              ->columns($columns)
              ->values($values)
              ->execute('exclusion_lists_staging');
    }

    /**
     * @param $rowData
     * @param $newHash
     * @return object
     */
    protected function updateRecords($rowData, $newHash)
    {
        $affectedRows = DB::update('sam_records_temp')
            ->set($rowData)
            ->where(DB::expr("HEX(new_hash)"), '=', strtoupper($newHash))
            ->execute('exclusion_lists_staging');
        return $affectedRows;
    }

    private function mapColumns($array)
    {
        $columns = [];
        foreach ($array as $key => $value)
        {
            if (in_array($column = $this->toSnakeCase($value), $this->columnsToImport)) {
                $columns[$key] = $column;
                continue;
            }

            if (array_key_exists($value, self::$columnMappings)) {
                $columns[$key] = self::$columnMappings[$value];
            }
        }

        return $columns;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function toSnakeCase($value)
    {
        return preg_replace('/[\s\-]/', '_', $value);
    }

    /**
     * @param $parsedUrl
     * @param $filepath
     * @return int
     */
    protected function getFileFromSam($parsedUrl, $filepath)
    {
        $curlOpts = [
            HTTPClient::CURL_OPTIONS => [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]
        ];
        $guzzleClient = new HTTPClient('https://' . $parsedUrl['host'], $curlOpts);

        $this->logCli('Retrieving Data...');

        if ($fileData = $guzzleClient->get(substr($parsedUrl['path'], 1))->send()) {
            file_put_contents($filepath, $fileData->getBody(true));
        };

        return ($fileData->getStatusCode() < 300);
    }

    private function unzipFile($filepath)
    {
        $zip = new ZipArchive();
        if (! $response = $zip->open($filepath) === true) {
            $this->logCli('Failed to open zip file ' . $response);
            return false;
        }

        if (! $zip->extractTo($dir = dirname($filepath))) {
            $this->logCli('Failed to extract zip file.');
        }

        $zip->close();

        return $dir .'/';

    }

	/**
	 * @param $currentRecords
	 * @param $activeRecordHashes
	 * @return array
	 */
	protected function getRecordsToDeactivate($currentRecords, $activeRecordHashes)
	{
		return array_keys(array_diff_key($currentRecords, array_flip($activeRecordHashes)));
	}

	protected function deactivate($toDeactivateHexHashes)
	{
        $sql = "UPDATE sam_records_temp SET Record_Status = 0 WHERE new_hash IN (unhex('" . implode("'),unhex('", $toDeactivateHexHashes) . "'))";

        $affectedRows = 0;
        $affectedRows += DB::query(Database::UPDATE, DB::expr($sql))
                            ->execute('exclusion_lists_staging');

		return $affectedRows;
	}
}
