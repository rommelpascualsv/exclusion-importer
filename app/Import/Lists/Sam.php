<?php namespace App\Import\Lists;

use App\Common\Entity\SamHash;
use App\Import\Lists\Sam\OigDuplicateRemover;
use App\Import\Lists\Sam\OpmExtrasRemover;
use App\Repositories\SamRepository;
use App\Import\Lists\Sam\SamService;
use App\Services\ExclusionListHttpDownloader;
use League\Csv\Reader;

class Sam extends ExclusionList
{
    private $samService;
    private $samRepository;
    private $opmExtrasRemover;
    private $oigDuplicateRemover;

    public $dbPrefix = 'sam';
    public $uri;
    public $isUriAutoGenerated = true;
    public $type = 'zip';
    private $columns = [
        'Classification','Name','Prefix','First','Middle','Last','Suffix','Address_1',
        'Address_2','Address_3','Address_4','City','State','Country','Zip','DUNS',
        'Exclusion_Program','Excluding_Agency','CT_Code','Exclusion_Type','Additional_Comments',
        'Active_Date','Termination_Date','Record_Status','Cross_Reference','SAM_Number'
    ];

    private $toCreate = [];


    const BROKEN_HASHES_SQL = <<<SQL
SELECT count(1) as row_count FROM (
SELECT *
FROM
(
SELECT *
FROM sam_records a
UNION ALL
SELECT *

FROM sam_records_temp b
) t
GROUP BY t.hash
HAVING COUNT(*) = 1) AS TEMP;
SQL;


    const RENAME_TEMP_SQL = <<<SQL
rename table sam_records TO sam_%s, sam_records_temp TO sam_records
SQL;


    public function __construct()
    {
        $this->samService = new SamService();
        $this->samRepository = new SamRepository();
        $this->opmExtrasRemover = new OpmExtrasRemover();
        $this->oigDuplicateRemover = new OigDuplicateRemover();

        $this->uri = $this->samService->getUrl();
    }


    public function getUrl()
    {
        return $this->samService->getUrl();
    }


    public function retrieveData()
    {
        $this->samService->extractZip($this->uri);
    }


    /**
     * Delete temp table
     * Create new temp table using the fields and data
     * of the active table
     */
    private function prepareTempTable()
    {
        app('db')->statement("DROP TABLE IF EXISTS " .SamRepository::SAM_TEMP_TABLE_NAME);
        app('db')->statement("CREATE TABLE IF NOT EXISTS " .SamRepository::SAM_TEMP_TABLE_NAME. " LIKE " .SamRepository::SAM_TABLE_NAME);
        app('db')->statement("INSERT INTO " .SamRepository::SAM_TEMP_TABLE_NAME. " SELECT * FROM " .SamRepository::SAM_TABLE_NAME);

        info('Temp table ready.');
    }


    /**
     *  Get all the new_hash of the active table
     *
     * @return array
     */
    private function getHashOfCurrentRecords()
    {
        $collection =  collect(app('db')->table(SamRepository::SAM_TABLE_NAME)
            ->select('*')
            ->addSelect(app('db')->raw('HEX(new_hash) as hex_new_hash'))
            ->get());
        return $collection->keyBy('hex_new_hash')->all();

    }

    private function getUniqueRows($csvContent)
    {
        $rows = [];

        // this loop will insert new records and update the existing
        foreach ($csvContent as $key => $value) {

            if ($key == 0) {
                continue;
            }

            $value = $this->sanitizeStringArray($value);

            $data = array_combine($this->columns, array_intersect_key($value, $this->columns));

            $data['Record_Status'] = 1;

            $activeDate = strtotime($data['Active_Date']);
            $data['Active_Date'] = ($activeDate) ? strftime('%Y-%m-%d', $activeDate) : NULL;

            try {
                $newHash = new SamHash($data);
            } catch (\InvalidArgumentException $e) {
                $this->warn('An error occurred at value ' . $key . ': ' . $e->getMessage());
                continue;
            }

            $rows[(string)$newHash] = $data;

        }
        return $rows;
    }


    private static function getUnhexValue($value)
    {
        return app('db')->raw("UNHEX('{$value}')");
    }


    /**
     * Removes double quote in the String
     *
     * @param $value - array of String
     *
     * @return array of String
     */
    private function sanitizeStringArray($value)
    {
        $rawString = implode('|',$value);
        $sanitized = str_replace('"', '', $rawString);
        $newValue = explode("|", $sanitized);
        return $newValue;
    }


    private function getRecordsToDeactivate($currentRecords, $activeRecordHashes)
    {
        return array_keys(array_diff_key($currentRecords, array_flip($activeRecordHashes)));
    }


    private function checkSamRecordStats()
    {
        $totalRecordsInTempTable = app('db')->table(SamRepository::SAM_TEMP_TABLE_NAME)->count();

        $totalRecordsInOriginalTable = app('db')->table(SamRepository::SAM_TABLE_NAME)->count();

        if ($totalRecordsInOriginalTable > 0 && (abs(intval($totalRecordsInOriginalTable) - intval($totalRecordsInTempTable)) > 800)) {
            $pattern = "There are %d new SAM records\nThere are %d original SAM records. Halting migration...";
            throw new \Exception(sprintf($pattern, $totalRecordsInTempTable, $totalRecordsInOriginalTable));
        }

        $result = app('db')->select(self::BROKEN_HASHES_SQL);
        $brokenHashCount = $result[0]->row_count;
        if ($totalRecordsInOriginalTable > 0 && (intval($brokenHashCount) / intval($totalRecordsInOriginalTable) > 0.05)) {
            $pattern = "There are %d new/broken hashes in the database which is above the current normal threshold. Halting migration...";
            throw new \Exception(sprintf($pattern, $brokenHashCount));
        }

        info('Done checking Sam Record stats');

    }


    private function finalizeRecord()
    {
        $this->checkSamRecordStats();

        app('db')->statement(app('db')->raw(sprintf(self::RENAME_TEMP_SQL, strftime('%Y%m%d_%H%M%S'))));

        info('Done moving to prod.');
    }


    public function doUpdate()
    {
        $startTime = microtime(true);

        $downloadDirectory = storage_path(ExclusionListHttpDownloader::DEFAULT_DOWNLOAD_DIRECTORY) . '/';

        $csvFileLocation = $downloadDirectory .$this->samService->getFileName() .'.CSV';

        $csv = Reader::createFromPath($csvFileLocation);

        $this->prepareTempTable();

        $hashOfCurrentRecords = $this->getHashOfCurrentRecords();

        $hashOfActiveRecords = [];

        $csvContent = $csv->fetch();

        $uniqueRowsInFile = $this->getUniqueRows($csvContent);

        $totalRecordCount = count($uniqueRowsInFile);

        $rowCnt = 1;

        foreach ($uniqueRowsInFile as $hash => $data) {
            // new record
            if (! array_key_exists($hash, $hashOfCurrentRecords)) {
                $data['hash'] = self::getUnhexValue($hash);
                $data['new_hash'] = self::getUnhexValue($hash);
                $this->toCreate[] = $data;
                
                if (sizeof($this->toCreate) == 10 || $rowCnt == $totalRecordCount) {
                    $this->samRepository->create($this->toCreate);
                    unset($this->toCreate);
                }

            } else { // existing record
                $hashOfActiveRecords[] = $hash;

                // update existing record
                $currentRecord = array_intersect_key($hashOfCurrentRecords[$hash], $data);
                $currentRecord['Record_Status'] = (int)$currentRecord['Record_Status'];
                if ( $data !== $currentRecord) {
                    $this->samRepository->updateRecord($data, $hash);
                }
            }
            $rowCnt++;
        }

        info('Done inserting records.');

        $toDeactivate = $this->getRecordsToDeactivate($hashOfCurrentRecords, $hashOfActiveRecords);
        $this->samRepository->deactivateRecordsByHash($toDeactivate);

        $this->opmExtrasRemover->invoke();

        $this->oigDuplicateRemover->invoke();

        $this->checkSamRecordStats();

        $this->finalizeRecord();

        $endTime = microtime(true);
        info('Execution time is ' .date("H:i:s",$endTime - $startTime));

    }

}
