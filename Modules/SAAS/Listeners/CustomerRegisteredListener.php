<?php

namespace Modules\SAAS\Listeners;

use App\Models\User;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\SAAS\Events\CustomerRegisteredEvent;
use Modules\SAAS\Emails\CustomerRegistrationConfirmationMail;

class CustomerRegisteredListener
// class CustomerRegisteredListener implements ShouldQueue
{
    public function __construct() {}
    // public function __construct(public CustomerRegisteredEvent $event)
    // {
    //     dd($event->user);
    // }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(object $event) : void
    {
        // Mail::to($event->user->email)->send(new CustomerRegistrationConfirmationMail($event->user));
        dd($event->user instanceof MustVerifyEmail,$event->user->hasVerifiedEmail());
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
