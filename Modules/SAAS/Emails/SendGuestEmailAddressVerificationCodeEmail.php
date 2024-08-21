<?php

namespace Modules\SAAS\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;

class SendGuestEmailAddressVerificationCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $code;
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */

     public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification Code.',
        );
    }

    public function build(): self
    {
        $code = $this->code;
        return $this->view('saas::mail.verification-code', compact('code'));
    }
}
