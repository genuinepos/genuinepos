<?php

namespace Modules\SAAS\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;

class SendTrialMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private $data, private $appUrl, private $trialExpireDate)
    {
    }
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trial has been created successfully.',
        );
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $data = $this->data;
        $appUrl = $this->appUrl;
        $trialExpireDate = $this->trialExpireDate;

        return $this->view('saas::mail.trial', compact('data', 'appUrl', 'trialExpireDate'));
    }
}
