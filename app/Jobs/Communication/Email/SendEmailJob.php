<?php

namespace App\Jobs\Communication\Email;

use App\Events\EmailConfiguration;
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
        Event::dispatch(new EmailConfiguration());
        $data["email"] = $this->recipient;
        $data["subject"] = $this->subject;
        $data["body"] = $this->message;
        $files = $this->attachments;
        Mail::send('mail.welcome.default', compact('data', 'files'), function ($message) use ($data, $files) {

            $message->to($data["email"])->subject($data["subject"]);

            foreach ($files as $file) {
                $message->attach($file);
            }
        });

        // Delete attachments after sending email
        foreach ($files as $file) {

            if (file_exists($file)) {

                $imagePath = public_path('uploads/communication/email/attachment/' . $file);

                if (file_exists($imagePath)) {

                    unlink($imagePath);
                }
            }
        }
    }
}
