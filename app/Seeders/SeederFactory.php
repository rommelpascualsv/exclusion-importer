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
        if ('njcredential' === $prefix) {
            return $this->app->make(\App\Seeders\NJCredential\UpdateSeeder::class);

        } else if ('njna' === $prefix) {
            return $this->app->make(\App\Seeders\Njna\CreateSeeder::class);
            
        } else if ('nppes' === $prefix) {
            return $this->app->make(\App\Seeders\Nppes\UpdateSeeder::class);
        }

        throw new \RuntimeException('No appropriate credential seeder found for ' . $prefix);
    }
}
