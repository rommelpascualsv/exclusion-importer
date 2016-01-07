<?php

class Task_DeleteOIGDuplicates extends Minion_Task
{

    /**
     * @var \Service\Logger\ExceptionLoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        parent::__construct();

        ini_set('memory_limit', '2048M');
        $this->initLogger();
    }

    public function _execute(array $params)
    {
        $this->logStdOut('Starting...');
        try {
            $this->logStdOut('Begun marking OIG duplicates');
            $markedDuplicateOIGRecords = $this->markOIGDuplicates();
            $this->logStdOut($markedDuplicateOIGRecords . " were marked as duplicate OIG records");

            $this->logStdOut('Begun deleting OIG duplicates');
            $removedDuplicateOIGRecords = $this->removeOIGDuplicates();
            $this->logStdOut($removedDuplicateOIGRecords . " were deleted as duplicate OIG records");
        } catch (\Exception $e) {
            $this->logger->logStackTrace($e, 'app.log');
            $this->logStdOut($e->getMessage() . ' Check the logs');
            die;
        }

    }

    private function markOIGDuplicates()
    {
        $query = DB::update()
                   ->table(DB::expr('sam_records_temp
					 JOIN oig_records
					 ON
					 oig_records.firstname = sam_records_temp.First
					 AND oig_records.lastname = sam_records_temp.Last
					 AND oig_records.excldate <= sam_records_temp.Active_Date'))
                   ->set(array(
                       'sam_records_temp.matching_OIG_hash' => DB::expr('oig_records.hash')
                   ))
                   ->where('sam_records_temp.First', '!=', '')
                   ->where('sam_records_temp.Last', '!=', '')
                   ->where('sam_records_temp.Excluding_Agency', '=', 'HHS');

        $records_updated = $query->execute('exclusion_lists_staging');

        if ($records_updated == null) {
            throw new \Exception('No records updated');
        }

        return $records_updated;
    }


    private function removeOIGDuplicates()
    {
        $total_records_deleted = DB::delete()
                                   ->table('sam_records_temp')
                                   ->where('matching_OIG_hash', '!=', DB::expr('UNHEX(\'00000000000000000000000000000000\')'))
                                   ->execute('exclusion_lists_staging');

        if ($total_records_deleted == null) {
            throw new Exception('No records deleted!');
        }
        return $total_records_deleted;
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
