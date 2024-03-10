<?php

namespace App\Jobs;

use App\Mail\TestEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipient;
    public $message;


    public function __construct($recipient, $message)
    {
        $this->recipient = $recipient;
        $this->message = $message;
    }

    public function handle()
    {


        return $this->recipient;

        
        // Mail::to($this->recipient)
        //     ->send(new TestEmail($this->message));
    }
}
