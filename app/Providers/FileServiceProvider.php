<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FileService;

class FileServiceProvider extends ServiceProvider
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
    	$this->app->singleton('App\Services\Contracts\FileServiceInterface', function($app)
    	{
    		return new FileService();
    	});
    	
//     	$this->app->bind('App\Services\Contracts\FileServiceInterface', 'App\Services\FileService');
    }
}
