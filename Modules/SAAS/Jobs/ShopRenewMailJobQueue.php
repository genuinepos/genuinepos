<?php

namespace Modules\SAAS\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Modules\SAAS\Emails\ShopRenewMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class ShopRenewMailJobQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $user, private $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new ShopRenewMail(user: $this->user, data: $this->data);
        Mail::to($this->user->email)->send($email);
    }
}
