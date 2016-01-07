<?php

class Task_MigrateSam extends Minion_Task
{

    /**
     * @var \Service\Logger\ExceptionLoggerInterface
     */
    protected $logger;

    const BROKEN_HASHES_SQL = <<<SQL
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
HAVING COUNT(*) = 1;
SQL;

    const RENAME_TEMP_SQL = <<<SQL
rename table sam_records TO sam_%s, sam_records_temp TO sam_records
SQL;

    public function __construct()
    {
        parent::__construct();


        ini_set('memory_limit', '2048M');
        $this->initLogger();
    }

    protected function _execute(array $params)
    {
        try {
            $this->logStdOut('Checking record data stats');
            $this->checkSamRecordStats();
            $this->logStdOut('Stats look good!');
            $this->logStdOut('Starting to move SAM temp table to production table');
            $this->moveTempToProd();
            $this->logStdOut('DONE!');
        } catch (\Exception $e) {
            $this->logStdOut('ERROR: ' . $e->getMessage());
            $this->logger->logStackTrace($e, 'app.log');
        }
    }

    protected function moveTempToProd()
    {
            DB::query(Database::UPDATE, DB::expr(sprintf(self::RENAME_TEMP_SQL, strftime('%Y%m%d_%H%M%S'))))
              ->execute('exclusion_lists_staging');
    }

    private function checkSamRecordStats()
    {
        $totalRecordsInTempTable = DB::query(Database::SELECT, DB::expr("SELECT COUNT(id) AS 'COUNT' FROM sam_records_temp"))
                                    ->execute('exclusion_lists_staging')
                                    ->as_array()[0]['COUNT'];

        $totalRecordsInOriginalTable = DB::query(Database::SELECT, DB::expr("SELECT COUNT(id) AS 'COUNT' FROM sam_records"))
                                        ->execute('exclusion_lists_staging')
                                        ->as_array()[0]['COUNT'];

        if (abs_diff(intval($totalRecordsInOriginalTable), intval($totalRecordsInTempTable)) > 800) {
            $pattern = "There are %d new SAM records\nThere are %d original SAM records. Halting migration...";
            throw new \Exception(sprintf($pattern, $totalRecordsInTempTable, $totalRecordsInOriginalTable));
        }

        $brokenHashCount = DB::query(Database::SELECT, DB::expr(self::BROKEN_HASHES_SQL))
                            ->execute('exclusion_lists_staging')
                            ->count();

        if (intval($brokenHashCount) / intval($totalRecordsInOriginalTable) > 0.05) {
            $pattern = "There are %d new/broken hashes in the database which is above the current normal threshold. Halting migration...";
            throw new \Exception(sprintf($pattern, $brokenHashCount));
        }

    }

    private function initLogger()
    {
        global $container;
        $this->logger = $container['logger.exceptions'];
    }

    private function logStdOut($string)
    {
        log_stdout($string);
    }
}
