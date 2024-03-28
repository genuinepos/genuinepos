<?php

namespace App\Jobs\Communication\Sms;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Traits\Communication\Sms\SmsConfiguration;

class SendManualSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SmsConfiguration;

    public $senderId;
    public $recipient;
    public $message;


    public function __construct($senderId, $recipient, $message)
    {
        $this->senderId = $senderId;
        $this->recipient = $recipient;
        $this->message = $message;
    }

    public function handle()
    {
    
       return  $this->sendSmsManual($this->senderId, $this->recipient, $this->message);

    }
}
