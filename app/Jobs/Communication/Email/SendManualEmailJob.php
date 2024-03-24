<?php

namespace App\Jobs\Communication\Email;

use Illuminate\Bus\Queueable;
// use App\Events\EmailConfiguration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Traits\Communication\Email\MenualEmailConfiguration;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class SendManualEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MenualEmailConfiguration;

    public $recipient;

    public $subject;

    public $message;

    public $cc;
    
    public $bcc;

    public function __construct($recipient, $subject, $message, $cc, $bcc)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->message = $message;
        $this->cc = $cc;
        $this->bcc = $bcc;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Event::dispatch(new EmailConfiguration());
        $data["email"] = $this->recipient;
        $data["subject"] = $this->subject;
        $data["body"] = $this->message;
        $data["cc"] =   $this->cc;
        $data["bcc"] = $this->bcc;
        $files = [];
        Mail::send('mail.welcome.default', compact('data', 'files'), function ($message) use ($data, $files) {
            $message->to($data["email"])
                ->subject($data["subject"])->cc($data["cc"])->bcc($data["bcc"]);
        });
    }
}
