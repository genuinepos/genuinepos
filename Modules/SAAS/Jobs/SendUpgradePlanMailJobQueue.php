<?php

namespace Modules\SAAS\Jobs;

use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\SAAS\Emails\SendUpgradePlanMail;

class SendUpgradePlanMailJobQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $user, private $data, private $planName, private $appUrl)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new SendUpgradePlanMail(user: $this->user, data: $this->data, planName: $this->planName, appUrl: $this->appUrl);
        Mail::to($this->user->email)->send($email);
    }
}
