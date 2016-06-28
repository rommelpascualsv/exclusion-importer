<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ImportSDN::class,
        \App\Console\Commands\ImportSam::class,
        \App\Console\Commands\DeleteOIGDuplicates::class,
        \App\Console\Commands\DeleteOPMExtras::class,
        \App\Console\Commands\MigrateSam::class,
        \App\Console\Commands\Nppes\Seed::class,
        \App\Console\Commands\Nppes\Update::class,
        \App\Console\Commands\Nppes\Deactivate::class,
        \App\Console\Commands\Taxonomy\Seed::class,
        \App\Console\Commands\Taxonomy\Clear::class,
        \App\Console\Commands\Njna\Seed::class,
        \App\Console\Commands\Njna\Clear::class,
        \App\Console\Commands\NJCredential\Seed::class,
        \App\Console\Commands\NJCredential\Update::class,
        \App\Console\Commands\MICna\Seed::class,
        \App\Console\Commands\MICna\Clear::class,
    	\App\Console\Commands\UpdateFiles::class,
        \App\Console\Commands\NJCredential\Import::class,
        \App\Console\Commands\Nppes\Import::class,
        \App\Console\Commands\Njna\Import::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('updateFiles')->daily();
        $schedule->command('njcredential:import')->weekly();
        $schedule->command('nppes:import')->weekly();
    }
}
