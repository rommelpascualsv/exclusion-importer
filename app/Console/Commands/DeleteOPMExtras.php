<?php

class Task_DeleteOPMExtras extends Minion_Task
{

    /**
     * @var \Service\Logger\ExceptionLoggerInterface
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

    protected function _execute(array $params)
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
        global $container;
        $this->logger = $container['logger.exceptions'];
    }

    private function deleteOPMExtras()
    {
        $query = DB::query(Database::DELETE, DB::expr(self::DELETE_OPM_SQL));
        return $query->execute('exclusion_lists_staging');
    }

    private function logStdOut($string)
    {
        log_stdout($string);
    }
}
