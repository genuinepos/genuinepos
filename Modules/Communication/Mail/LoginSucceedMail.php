<?php

namespace Modules\Communication\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginSucceedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $MyData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($MyData)
    {
        $this->MyData = $MyData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('mail.templates.welcome');
    }
}
