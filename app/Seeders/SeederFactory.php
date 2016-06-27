<?php

namespace App\Seeders;

use Illuminate\Container\Container;

class SeederFactory
{
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param $prefix
     * @return Seeder
     */
    public function createSeeder($prefix)
    {
        if ($prefix === 'njcredential') {
            return $this->app->make(\App\Seeders\NJCredential\CreateSeeder::class);

        } else if ($prefix === 'njna') {
            return $this->app->make(\App\Seeders\Njna\CreateSeeder::class);
            
        } else if ($prefix === 'nppes') {
            return $this->app->make(\App\Seeders\Nppes\CreateSeeder::class);
        }

        throw new \RuntimeException('No appropriate credential seeder found for ' . $prefix);
    }
}