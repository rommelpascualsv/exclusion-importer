<?php namespace App\Console\Commands\Taxonomy;

use App\Console\Commands\BaseSeed;
use App\Seeders\Taxonomy\CreateSeeder;

class Seed extends BaseSeed 
{
    protected $name = 'taxonomy:seed';

    protected $description = 'Seeds the taxonomy collection in mongo with the file provided';
    
    protected $database = 'Taxonomy';

    public function __construct(CreateSeeder $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }
}
