<?php namespace App\Console\Commands\Nppes;

use App\Console\Commands\BaseSeed;
use App\Seeders\Nppes\CreateSeeder;

class Seed extends BaseSeed {

    protected $name = 'nppes:seed';

    protected $description = 'Seeds the nppes collection in mongo with the file provided';
    
    protected $database = 'Nppes';

    public function __construct(CreateSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }
}
