<?php namespace App\Console\Commands\Njna;

use App\Console\Commands\BaseSeed;
use App\Seeders\Njna\CreateSeeder;

class Seed extends BaseSeed {

    protected $name = 'njna:seed';

    protected $description = 'Seeds the New Jersey Nurse Aide collection in mongo with the file provided';

    protected $database = 'New Jersey Nurse Aide';

    public function __construct(CreateSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }
}
