<?php

namespace Modules\SAAS\Jobs;

use Mail;
use Illuminate\Bus\Queueable;
use Modules\SAAS\Emails\AddShopMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddShopMailJobQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct(
        private $user,
        private $increasedShopCount,
        private $pricePerShop,
        private $pricePeriod,
        private $pricePeriodCount,
        private $subtotal,
        private $netTotalAmount,
        private $discount,
        private $totalPayable,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new AddShopMail(
            user: $this->user,
            increasedShopCount: $this->increasedShopCount,
            pricePerShop: $this->pricePerShop,
            pricePeriod: $this->pricePeriod,
            pricePeriodCount: $this->pricePeriodCount,
            subtotal: $this->subtotal,
            netTotalAmount: $this->netTotalAmount,
            discount: $this->discount,
            totalPayable: $this->totalPayable,
        );

        Mail::to($this->user->email)->send($email);
    }
}
