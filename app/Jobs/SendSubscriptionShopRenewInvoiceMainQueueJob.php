<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendSubscriptionShopRenewInvoiceMail;
use Mail;

class SendSubscriptionShopRenewInvoiceMainQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $user, private $data)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email =  new SendSubscriptionShopRenewInvoiceMail(
            user: $this->user,
            data: $this->data,
        );

        Mail::to($this->user->email)->send($email);
    }
}
