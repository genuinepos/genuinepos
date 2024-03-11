<?php

namespace App\Listeners;

use App\Models\Communication\Email\EmailServer;

use Illuminate\Support\Facades\Config;


class TenantBootstrapped
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // $configuration = EmailServer::where('status', 1)->first();

        // if (isset($configuration)) {

        //     Config::set('mail.mailers.smtp.host', $configuration->host);
        //     Config::set('mail.mailers.smtp.port', $configuration->port);
        //     Config::set('mail.mailers.smtp.username', $configuration->user_name);
        //     Config::set('mail.mailers.smtp.password', $configuration->password);
        //     Config::set('mail.mailers.smtp.encryption', $configuration->encryption);
        // }
        // else {

        //     throw new \Exception("No active email server configuration found.");
        // }

    }
}
