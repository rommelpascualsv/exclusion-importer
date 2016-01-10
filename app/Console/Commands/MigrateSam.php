<?php namespace App\Console\Commands;

use App\Common\Logger;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Illuminate\Console\Command;

class MigrateSam extends Command
{

    /**
     * @var \App\Common\Logger\ExceptionLoggerInterface
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

    protected function fire()
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
        app('db')->statement(app('db')->raw(sprintf(self::RENAME_TEMP_SQL, strftime('%Y%m%d_%H%M%S'))));
    }

    private function checkSamRecordStats()
    {
        $totalRecordsInTempTable = app('db')->table('sam_records_temp')->count();

        $totalRecordsInOriginalTable = app('db')->table('sam_records')->count();

        if (abs(intval($totalRecordsInOriginalTable) - intval($totalRecordsInTempTable)) > 800) {
            $pattern = "There are %d new SAM records\nThere are %d original SAM records. Halting migration...";
            throw new \Exception(sprintf($pattern, $totalRecordsInTempTable, $totalRecordsInOriginalTable));
        }

        $brokenHashCount = app('db')->statement(self::BROKEN_HASHES_SQL)->count();

        if (intval($brokenHashCount) / intval($totalRecordsInOriginalTable) > 0.05) {
            $pattern = "There are %d new/broken hashes in the database which is above the current normal threshold. Halting migration...";
            throw new \Exception(sprintf($pattern, $brokenHashCount));
        }

    }

    private function initLogger()
    {
        $this->logger = new Logger\ExceptionLogger(new Filesystem(new Local(storage_path('app') . '/logs/')));
    }

    private function logStdOut($string)
    {
        fwrite(STDOUT, sprintf('[ %s ] %s' . PHP_EOL, strftime('%Y-%m-%d %H:%M:%S'), $string));
    }
}
