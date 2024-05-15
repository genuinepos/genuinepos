<?php

namespace Modules\SAAS\Jobs;

use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Modules\SAAS\Emails\SendTrialMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTrialMailJobQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private $data, private $appUrl, private $trialExpireDate)
    {
    }

    public function handle(): void
    {
        $email = new SendTrialMail(data: $this->data, appUrl: $this->appUrl, trialExpireDate: $this->trialExpireDate);
        Mail::to($this->data['email'])->send($email);
    }
}
