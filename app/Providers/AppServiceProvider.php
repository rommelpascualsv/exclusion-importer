<?php

namespace App\Providers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Filesystem::class, function($app) {
            return $app['filesystem']->disk();
        });
    }
}
