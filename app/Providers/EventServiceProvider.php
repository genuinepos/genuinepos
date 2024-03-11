<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\EmailNotified' => [
            'App\Listeners\SendEmailNotification',
        ],
        'Illuminate\Auth\Events\Authenticated' => [
            'App\Listeners\GeneralSettingsListener',
        ],
    ];

    protected $priorities = [
        'App\Listeners\GeneralSettingsListener' => 100,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
