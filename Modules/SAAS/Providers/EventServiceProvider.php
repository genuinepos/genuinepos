<?php

namespace Modules\SAAS\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\SAAS\Events\CustomerRegisteredEvent;
use Modules\SAAS\Listeners\CustomerRegisteredListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CustomerRegisteredEvent::class => [
            CustomerRegisteredListener::class,
        ],
    ];

    public function boot()
    {

    }
}
