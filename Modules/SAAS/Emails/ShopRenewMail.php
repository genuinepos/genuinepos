<?php

namespace Modules\SAAS\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;

class ShopRenewMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private $user, private $data) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Store/Company Renew Invoice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): self
    {
        $user = $this->user;
        $data = $this->data;

        return $this->view('saas::mail.shop_renew_invoice', compact('user', 'data'));
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
