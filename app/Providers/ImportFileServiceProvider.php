<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ImportFileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->app->bind('App\Services\Contracts\ImportFileServiceInterface', 'App\Services\ImportFileService');
    }
}