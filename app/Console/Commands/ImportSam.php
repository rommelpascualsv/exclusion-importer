<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client as HTTPClient;
use App\Common\File;
use App\Common\Entity\SamHash;

class ImportSam extends Command 
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sam:import {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the most awesome SAM database.';

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

    private $header = [
        'Classification','Name','Prefix','First','Middle','Last','Suffix'
    ];

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->columnsToImport = $this->getDBColumns();

        ini_set('memory_limit', '2048M');

    }

    private function prepareTempTable()
    {
        $this->info('Preparing temp table...');

        $tableName = 'sam_records';
        $tempTableName = 'sam_records_temp';

        app('db')->statement("DROP TABLE IF EXISTS $tempTableName");
        app('db')->statement("CREATE TABLE IF NOT EXISTS $tempTableName LIKE $tableName");
        app('db')->statement("INSERT INTO $tempTableName SELECT * FROM $tableName");
        $this->info('temp table ready!');
    }

    public function fire()
    {
        $parsedUrl = parse_url($this->argument('url'));

        $filepath = tempnam(storage_path('app') . '/temp/', 'samdb');

        if (! $this->getFileFromSam($parsedUrl, $filepath)) {
            $this->error('Could not retrieve file from ' . $filepath);
            die;
        }
        $this->info('Put ZIP file in ' . $filepath);

        if (! $unzippedFileDir = $this->unzipFile($filepath)) {
            die;
        }

        $unzippedFilepath = $unzippedFileDir . str_ireplace('.ZIP', '.CSV', basename($parsedUrl['path']));

        $this->info('Put UnZIPed file in ' . $unzippedFilepath);

        $this->prepareTempTable();

        $file = new File($unzippedFilepath);
        $file->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(',', "\"", "|");

        $this->info('Getting all current records...');
        $currentRecords = $this->getCurrentRecords();
        $this->info('Finished getting all current records');

        $columnsInFile = $this->mapColumns($file->fgetcsv());
        $this->info('Importing columns ' . implode(',', $columnsInFile));

        $totalLines = $file->getTotalLines();
        $this->info('Total lines in file: ' . $totalLines);

        $uniqueRowsInFile = $this->getUniqueRows($file, $totalLines, $columnsInFile);
        $this->info('Total unique lines in file: ' . count($uniqueRowsInFile));

        $activeRecordHashes = [];
		$updated = 0;
        $skipped = 0;
        $total = 0;

        foreach ($uniqueRowsInFile as $hash => $rowData) {
            $total++;
            if (! array_key_exists($hash, $currentRecords)) {
                $rowData['hash'] = app('db')->raw("UNHEX('{$hash}')");
                $rowData['new_hash'] = app('db')->raw("UNHEX('{$hash}')");
                // can we just create it here?!?!
                $this->toCreate[] = $rowData;
            }
            else {
				$activeRecordHashes[] = $hash;
                $currentRecord = array_intersect_key($currentRecords[$hash], $rowData);
                $currentRecord['Record_Status'] = (int)$currentRecord['Record_Status'];
                if ($rowData !== $currentRecord) {
                    $affectedRows = $this->updateRecords($rowData, $hash);
                    $updated += $affectedRows;
                } else {
                    $skipped++;
                }
            }

            if ($total % 1000 === 0) {
                $statsPattern = 'Total unique lines processed: %d -- Total Records to Create: %d -- Total Records Updated: %d -- Total Lines Skipped: %d';
                $this->info(sprintf($statsPattern, $total, count($this->toCreate), $updated, $skipped));
            }
        }

		$this->info('Creating new records...');
        $this->createNewRecords($this->toCreate);
        $this->info('Finished creating new records');

		$toDeactivate = $this->getRecordsToDeactivate($currentRecords, $activeRecordHashes);
		$this->info(count($toDeactivate) . ' Total to Deactivate.');
		$this->info('Deactivating...');
		$deactivated = $this->deactivate($toDeactivate);
		$this->info('Finished deactivating');

        $this->info('===================================================');

        $this->info($total . ' Total Unique Lines Processed From New File');
        $this->info(count($this->toCreate) . ' Records Created');
        $this->info($updated . ' Records Updated');
        $this->info($skipped . ' Lines Skipped');
        $this->info($deactivated . ' Total Records Deactivated');
        $this->info('DONE!');
        $this->info('===================================================');
    }

    /**
     * @param $file  File
     * @param $totalLines int
     * @param $columnsInFile array
     * @return array
     */
    private function getUniqueRows($file, $totalLines, $columnsInFile)
    {
        $rows = [];

        // Iterate csv
        foreach ($file->csvlineIterator($totalLines) as $row) {

            if (empty($row)) {
                break;
            }

            if ($this->isHeader($row)) {
                continue;
            }

            $rowData = array_combine($columnsInFile, array_intersect_key($row, $columnsInFile));
            $rowData['Record_Status'] = 1; // Set Record_Status to active since it exists in the file

            try {
                $newHash = new SamHash($rowData);
            } catch (\InvalidArgumentException $e) {
                $this->warn('An error occurred at row ' . $file->key() . ': ' . $e->getMessage());
                continue;
            }

            $unixActiveDate = strtotime($rowData['Active_Date']);

            $rowData['Active_Date'] = ($unixActiveDate)
                ? strftime('%Y-%m-%d', $unixActiveDate)
                : NULL;

            $rows[(string)$newHash] = $rowData;
        }
        return $rows;
    }

    private function getDBColumns()
    {
        $columns = app('db')->select("SHOW COLUMNS FROM sam_records");
        return array_map(function($column) {
            return $column->Field;
        }, $columns);
    }

    private function getCurrentRecords()
    {
        $collection =  collect(app('db')->table('sam_records')
            ->select('*')
            ->addSelect(app('db')->raw('HEX(new_hash) as hex_new_hash'))
            ->get());
        return $collection->keyBy('hex_new_hash')->all();

    }

    private function createNewRecords($toInsert)
    {
        $chunks = array_chunk($toInsert, 10);
        foreach ($chunks as $chunk) {
            app('db')->transaction(function () use ($chunk) {
                app('db')->table('sam_records_temp')->insert($chunk);
            });
        }
    }

    /**
     * @param $rowData
     * @param $newHash
     * @return object
     */
    protected function updateRecords($rowData, $newHash)
    {
        $affectedRows = app('db')->table('sam_records_temp')
            ->update($rowData)
            ->where(app('db')->raw("HEX(new_hash)"), strtoupper($newHash));

        return $affectedRows;
    }

    private function mapColumns($array)
    {
        $columns = [];
        foreach ($array as $key => $value) {
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
        $options = [
            'base_uri' => 'https://' . $parsedUrl['host'],
            'curl.options' => [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]
        ];
        $guzzleClient = new HTTPClient($options);

        $this->info('Retrieving Data...');

        if ($fileData = $guzzleClient->get(substr($parsedUrl['path'], 1))) {
            file_put_contents($filepath, $fileData->getBody());
        };

        return ($fileData->getStatusCode() < 300);
    }

    private function unzipFile($filepath)
    {
        $zip = new \ZipArchive();
        if (! $response = $zip->open($filepath) === true) {
            $this->error('Failed to open zip file ' . $response);
            return false;
        }

        if (! $zip->extractTo($dir = dirname($filepath))) {
            $this->error('Failed to extract zip file.');
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
        $affectedRows += app('db')->statement(app('db')->raw($sql));

		return $affectedRows;
	}

    private function isHeader($row)
    {
        $output = array_slice($row, 0, 7);
        return ($this->header == $output);
    }
}
