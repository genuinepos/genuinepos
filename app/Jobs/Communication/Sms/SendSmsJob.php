<?php

namespace App\Jobs\Communication\Sms;

use App\Mail\TestEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Traits\Communication\Sms\SmsConfiguration;
use Illuminate\Support\Facades\Mail;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SmsConfiguration;

    public $recipient;
    public $message;


    public function __construct($recipient, $message)
    {
        $this->recipient = $recipient;
        $this->message = $message;
    }

    public function handle()
    {

       return  $this->sendSms($this->recipient, $this->message);

    }
}
