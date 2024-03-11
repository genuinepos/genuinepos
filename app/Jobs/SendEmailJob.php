<?php

namespace App\Jobs;

use App\Events\EmailNotified;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipient;
    public $subject;
    public $message;
    public $attachments;

    public function __construct($recipient, $subject, $message, $attachments)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->message = $message;
        $this->attachments = $attachments;

    }

    public function handle()
    {
        Event::dispatch(new EmailNotified());
        $data["email"] = $this->recipient;
        $data["subject"] = $this->subject;
        $data["body"] = $this->message;
        $files = $this->attachments;
        Mail::send('mail.test', $data, function ($message) use ($data, $files) {
            $message->to($data["email"])
                ->subject($data["subject"]);
            foreach ($files as $file) {
                $message->attach($file);
            }
        });
    }
}
