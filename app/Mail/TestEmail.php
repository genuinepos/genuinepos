<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $attachments;

    public function __construct($subject, $message, $attachments = [])
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->attachments = $attachments;
    }

    public function build()
    {
        $mail = $this->subject($this->subject)
            ->view('mail.send_email')
            ->with([
                'message' => $this->message,
                'subject' => $this->subject,
                'attachments' => $this->attachments,
            ]);

        foreach ($this->attachments as $attachment) {
            $mail->attach($attachment['path'], [
                'as' => $attachment['name'],
                'mime' => $attachment['mime'],
            ]);
        }

        return $mail;
    }
}
