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
        '\App\Console\Commands\ImportSDN',
        '\App\Console\Commands\ImportSam',
        '\App\Console\Commands\DeleteOIGDuplicates',
        '\App\Console\Commands\DeleteOPMExtras',
        '\App\Console\Commands\MigrateSam',
    	'\App\Console\Commands\UpdateFiles',

        '\App\Console\Commands\Nppes\Seed',
        '\App\Console\Commands\Nppes\Update',
        '\App\Console\Commands\Nppes\Deactivate',

        '\App\Console\Commands\Taxonomy\Seed',
        '\App\Console\Commands\Taxonomy\Clear',

        '\App\Console\Commands\Njna\Seed',
        '\App\Console\Commands\Njna\Clear',

        '\App\Console\Commands\NJCredential\Seed',
        '\App\Console\Commands\NJCredential\Update',

        '\App\Console\Commands\MICna\Seed',
        '\App\Console\Commands\MICna\Clear',
    	'\App\Console\Commands\UpdateFiles',
        
        // Import/Scrape
        \App\Console\Commands\Scrape\Connecticut\ExtractCategories::class,
        \App\Console\Commands\Scrape\Connecticut\DownloadCsv::class,
        \App\Console\Commands\Scrape\Connecticut\ExtractCsvHeaders::class,
        \App\Console\Commands\Scrape\Connecticut\OrganizeCsvHeaders::class,
        \App\Console\Commands\Scrape\Connecticut\ImportCsv::class,
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
    }
}
