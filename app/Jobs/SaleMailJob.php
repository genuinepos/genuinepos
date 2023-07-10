<?php

namespace App\Jobs;

use App\Mail\SaleMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SaleMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $to;

    public $sale;

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
