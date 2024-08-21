<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionAddBusinessInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * Create a new message instance.
     */
    public function __construct(private $user, private $data)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Add Multi Store Management System Invoice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): self
    {
        $user = $this->user;
        $data = $this->data;

        return $this->view('mail.invoice.billing_add_business_invoice', compact('user', 'data'));
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
