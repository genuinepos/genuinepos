<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchReceiveStockDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $transfer;
    public $request;
    public function __construct($request,$transfer)
    {
        $this->transfer = $transfer;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $transfer = $this->transfer;
        $request = $this->request;
        return $this->view('mail.branch_stock_receive_mail', compact('request', 'transfer'));
    }
}
