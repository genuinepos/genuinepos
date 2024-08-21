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

    public function __construct(private $data, private $planName, private $appUrl)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new NewSubscriptionMail(data: $this->data, planName: $this->planName, appUrl: $this->appUrl);
        Mail::to($this->data['email'])->send($email);
    }
}
