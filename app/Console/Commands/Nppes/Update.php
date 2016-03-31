<?php namespace App\Console\Commands\Nppes;

use App\Console\Commands\BaseSeed;
use App\Seeders\Nppes\UpdateSeeder;

class Update extends BaseSeed {

    protected $name = 'nppes:update';

    protected $description = 'Updates the nppes collection in Mongo with the file provided';

    protected $database = 'Nppes';

    public function __construct(UpdateSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }

    protected function outputResults($results)
    {
        $this->info($results['succeeded'] . ' entries successfully updated or created');
        $this->error($results['failed'] . ' entries failed to be parsed');
        $this->info('Finished updating Nppes database');
    }
}
