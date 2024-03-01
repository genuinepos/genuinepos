<?php

namespace Modules\SAAS\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\SAAS\Emails\SendGuestEmailAddressVerificationCodeEmail;
use Mail;

class SendGuestVerificationCodeEmailQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $to;
    private $code;
    public function __construct($to, $code)
    {
        $this->to = $to;
        $this->code = $code;
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $to = $this->to;
        $code = $this->code;

        $email = new SendGuestEmailAddressVerificationCodeEmail($code);
        Mail::to($to)->send($email);
    }
}
