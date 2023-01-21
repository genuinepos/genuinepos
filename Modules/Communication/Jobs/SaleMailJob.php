<?php

namespace Modules\Communication\Jobs;

use Modules\Communication\Mail\SaleMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaleMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to;
    public $sale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $sale)
    {
        $this->to = $to;
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SaleMail($this->sale);
        Mail::to($this->to)->send($email);
    }
}
