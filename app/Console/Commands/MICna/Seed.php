<?php namespace App\Console\Commands\MICna;

use App\Console\Commands\BaseSeed;
use App\Seeders\MICna\CreateSeeder;

class Seed extends BaseSeed {

    protected $name = 'micna:seed';

    protected $description = 'Seeds the Michigan Certified Nurse Aide collection in mongo with the file provided';

    protected $database = 'Michigan Certified Nurse Aide';

    public function __construct(CreateSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }
}
