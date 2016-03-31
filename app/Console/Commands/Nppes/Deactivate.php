<?php namespace App\Console\Commands\Nppes;

use App\Console\Commands\BaseSeed;
use App\Seeders\Nppes\DeactivationSeeder;

class Deactivate extends BaseSeed {

    protected $name = 'nppes:deactivate';

    protected $description = 'Update deactivation properties for Nppes records in mongo with the file provided';

    protected $database = 'Nppes';

    public function __construct(DeactivationSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }

    protected function outputResults($results)
    {
        $this->info($results['succeeded'] . ' entries successfully deactivated');
        $this->error($results['failed'] . ' entries failed to be parsed');
        $this->info('Nppes deactivation finished');   
    }
}
