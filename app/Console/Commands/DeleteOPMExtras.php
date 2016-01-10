<?php namespace App\Console\Commands;

use App\Common\Logger;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Illuminate\Console\Command;

class DeleteOPMExtras extends Command
{

    /**
     * @var \App\Common\Logger\ExceptionLoggerInterface
     */
    protected $logger;

    const DELETE_OPM_SQL = <<<SQL
DELETE
	sam_records_temp_alias1
FROM
	sam_records_temp sam_records_temp_alias1
INNER JOIN sam_records_temp sam_records_temp_alias2 ON (
	sam_records_temp_alias1.Classification = sam_records_temp_alias2.Classification
)
AND (
	sam_records_temp_alias1. NAME = sam_records_temp_alias2. NAME
)
AND (
	sam_records_temp_alias1. FIRST = sam_records_temp_alias2. FIRST
)
AND (
	sam_records_temp_alias1.Middle = sam_records_temp_alias2.Middle
)
AND (
	sam_records_temp_alias1.Last = sam_records_temp_alias2.Last
)
AND (
	sam_records_temp_alias1.Address_1 = sam_records_temp_alias2.Address_1
)
AND (
	sam_records_temp_alias1.City = sam_records_temp_alias2.City
)
AND (
	sam_records_temp_alias1.State = sam_records_temp_alias2.State
)
AND (
	sam_records_temp_alias1.Zip = sam_records_temp_alias2.Zip
)
AND (
	sam_records_temp_alias1.Exclusion_Program = sam_records_temp_alias2.Exclusion_Program
)
AND (
	sam_records_temp_alias1.Exclusion_Type = sam_records_temp_alias2.Exclusion_Type
)
WHERE
	sam_records_temp_alias2.Excluding_Agency = 'HHS'
AND sam_records_temp_alias1.Excluding_Agency = 'OPM'
AND sam_records_temp_alias1.Active_Date > sam_records_temp_alias2.Active_Date;
SQL;


    public function __construct()
    {
        parent::__construct();

        ini_set('memory_limit', '2048M');
        $this->initLogger();
    }

    protected function fire()
    {
        try{
            $this->logStdOut('Started deleting...');
            $totalDeleted = $this->deleteOPMExtras();
            $this->logStdOut('Total Deleted: ' . $totalDeleted);
        } catch (\Exception $e) {
            $this->logger->logStackTrace($e, 'app.log');
            $this->logStdOut($e->getMessage());
        }
    }

    private function initLogger()
    {
        $this->logger = new Logger\ExceptionLogger(new Filesystem(new Local(storage_path('app') . '/logs/')));
    }

    private function deleteOPMExtras()
    {
        return app('db')->statement(self::DELETE_OPM_SQL);
    }

    private function logStdOut($string)
    {
        fwrite(STDOUT, sprintf('[ %s ] %s' . PHP_EOL, strftime('%Y-%m-%d %H:%M:%S'), $string));
    }
}
