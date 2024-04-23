<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendSubscriptionPlanUpgradeMail;
use Mail;

class SendSubscriptionPlanUpgradeMailQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $user, private $planName, private $data, private $isTrialPlan)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new SendSubscriptionPlanUpgradeMail(user: $this->user, planName: $planName, data: $this->data, isTrialPlan: $this->isTrialPlan);
        Mail::to($this->user->email)->send($email);
    }
}
