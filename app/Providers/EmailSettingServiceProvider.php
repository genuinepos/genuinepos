<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;

class EmailSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Inject Email Settings at Run-time
         */
        $mailSettings = GeneralSetting::email();
        if(isset($mailSettings)) {
            config([
                'mail.mailers.smtp.transport' => $mailSettings['MAIL_MAILER'],
                'mail.mailers.smtp.host' => $mailSettings['MAIL_HOST'],
                'mail.mailers.smtp.port' => $mailSettings['MAIL_PORT'],
                'mail.mailers.smtp.encryption' => $mailSettings['MAIL_ENCRYPTION'],
                'mail.mailers.smtp.username' => $mailSettings['MAIL_USERNAME'],
                'mail.mailers.smtp.password' => $mailSettings['MAIL_PASSWORD'],
                // 'mail.mailers.smtp.timeout' => $mailSettings->MAIL_TIMEOUT'],
                // 'mail.mailers.smtp.auth_mode' => $mailSettings->MAIL_AUTH_MODE'],
            ]);
        }
        // dd(config('mail.mailers'));
    }
}
