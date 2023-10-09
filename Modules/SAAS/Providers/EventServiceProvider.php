<?php

namespace Modules\SAAS\Providers;

use Modules\SAAS\Events\CustomerRegisteredEvent;
use Modules\SAAS\Listeners\CustomerRegisteredListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CustomerRegisteredEvent::class => [
            CustomerRegisteredListener::class,
        ]
    ];

    public function boot()
    {

    }

}
