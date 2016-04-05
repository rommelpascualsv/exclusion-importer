<?php namespace App\Console\Commands;

use App\Common\Logger;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Illuminate\Console\Command;

class DeleteOIGDuplicates extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sam:delete-oig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete IOG Dupes in the SAM database.';

    /**
     * @var \App\Common\Logger\ExceptionLoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        parent::__construct();

        ini_set('memory_limit', '2048M');
        $this->initLogger();
    }

    public function fire()
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
        $records_updated = app('db')
            ->table('sam_records_temp')
            ->join('oig_records', function ($join) {
                $join->on('oig_records.firstname', '=', 'sam_records_temp.First')
                    ->on('oig_records.lastname', '=', 'sam_records_temp.Last')
                    ->on('oig_records.excldate', '<=', 'sam_records_temp.Active_Date');
            })
            ->where('sam_records_temp.First', '!=', '')
            ->where('sam_records_temp.Last', '!=', '')
            ->where('sam_records_temp.Excluding_Agency', '=', 'HHS')
           ->update([
               'sam_records_temp.matching_OIG_hash' => app('db')->raw('oig_records.hash')
           ]);

        if ($records_updated == null) {
            throw new \Exception('No records updated');
        }

        return $records_updated;
    }

    private function removeOIGDuplicates()
    {
        $total_records_deleted = app('db')
            ->table('sam_records_temp')
            ->where('matching_OIG_hash', '!=', app('db')->raw('UNHEX(\'00000000000000000000000000000000\')'))
            ->delete();

        if ($total_records_deleted == null) {
            throw new \Exception('No records deleted!');
        }
        return $total_records_deleted;
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
