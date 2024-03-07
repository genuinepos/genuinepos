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
    private $to;
    private $user;
    public function __construct($to, $user)
    {
        $this->to = $to;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email =  new SendSubscriptionPlanUpgradeMail($this->user);
        Mail::to($this->to)->send($email);
    }
}
