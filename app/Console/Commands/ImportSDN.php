<?php namespace App\Console\Commands;

use App\Import\OFAC\SDN;
use Illuminate\Console\Command;

class ImportSDN extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'import:sdn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the Ofac SDN database.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Set the time limit to 2 Hours
        set_time_limit(7200);

        // Set memory limit to 1 GB
        ini_set('memory_limit', '1024M');
        
        $this->info('Starting...');
        $this->info('Loading OFAC SDN source file...');

        $sdn = new SDN();

        // Output the source file
        $sourceFile = $sdn->getSourceFile();
        $this->info("| Source File: {$sourceFile}");

        // Output the total entries
        $total_entries = $sdn->getTotalEntries();
        $this->info("| Total Entries: {$total_entries}");

        $this->info("| Saving to the database...");
        $sdn->saveToDatabase();

        $this->info("| Updating hashes...");
        app('db')->statement("UPDATE sdn_entries set `hash` = UNHEX(MD5(`uid`))");

        $this->info('done!');
    }

}
