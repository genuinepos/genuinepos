<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionPlanUpgradeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private $user, private $planName, private $data, private $isTrialPlan)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Plan Upgrade Mail',
        );
    }

    public function build(): self
    {
        $user = $this->user;
        $planName = $this->planName;
        $data = $this->data;
        $isTrialPlan = $this->isTrialPlan;
        return $this->view('mail.plan_upgrade', compact('user', 'planName', 'data', 'isTrialPlan'));
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
