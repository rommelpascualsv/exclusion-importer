<?php

namespace App\Providers;

use App\Listeners\EventPersister;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'file.*' => [EventPersister::class],
        'credential.*' => [EventPersister::class]
    ];
}
