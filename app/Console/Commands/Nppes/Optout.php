<?php namespace App\Console\Commands\Nppes;

use App\Console\Commands\BaseSeed;
use App\Seeders\Nppes\CreateSeeder;

class Optout extends BaseSeed
{
    protected $name = 'nppes:optout';

    protected $description = 'Updates the Opt out data of NPPES collection';

    protected $database = 'Nppes';

    public function __construct(CreateSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }
}
