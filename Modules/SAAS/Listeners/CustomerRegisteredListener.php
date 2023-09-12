<?php

namespace Modules\SAAS\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\SAAS\Emails\CustomerRegistrationConfirmationMail;
use Modules\SAAS\Events\CustomerRegisteredEvent;

class CustomerRegisteredListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user->email)->send(new CustomerRegistrationConfirmationMail($event->user));
    }
}
