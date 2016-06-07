<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExclusionListMgmtServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\Contracts\ExclusionListServiceInterface', 'App\Services\ExclusionListService');
        $this->app->bind('App\Services\Contracts\ImportFileServiceInterface', 'App\Services\ImportFileService');
    }
}
