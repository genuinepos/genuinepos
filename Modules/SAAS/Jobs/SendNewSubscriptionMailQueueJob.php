<?php

namespace Modules\SAAS\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\SAAS\Emails\NewSubscriptionMail;

class SendNewSubscriptionMailQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $to;
    private $user;

    /**
     * Create a new job instance.
     */
    public function __construct($to, $user)
    {
        $this->to = $to;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new NewSubscriptionMail($this->user);
        Mail::to($this->to)->send($email);
    }
}
