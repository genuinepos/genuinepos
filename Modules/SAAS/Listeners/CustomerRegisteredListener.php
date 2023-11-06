<?php

namespace Modules\SAAS\Listeners;

use App\Models\User;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\SAAS\Emails\CustomerRegistrationConfirmationMail;
use Modules\SAAS\Events\CustomerRegisteredEvent;

class CustomerRegisteredListener
// class CustomerRegisteredListener implements ShouldQueue
{
    public function __construct()
    {
    }
    // public function __construct(public CustomerRegisteredEvent $event)
    // {

    // }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // Mail::to($event->user->email)->send(new CustomerRegistrationConfirmationMail($event->user));
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
