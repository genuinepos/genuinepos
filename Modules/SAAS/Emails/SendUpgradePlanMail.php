<?php

namespace Modules\SAAS\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;

class SendUpgradePlanMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private $user, private $data, private $planName, private $isTrialPlan, private $appUrl)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Plan Upgraded',
        );
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $user = $this->user;
        $data = $this->data;
        $planName = $this->planName;
        $isTrialPlan = $this->isTrialPlan;
        $appUrl = $this->appUrl;

        return $this->view('saas::mail.upgrade_plan', compact('user', 'data', 'planName', 'isTrialPlan', 'appUrl'));
    }
}
