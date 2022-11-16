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
                'mail.mailers.smtp.transport' => $mailSettings['MAIL_MAILER'] ?? config('mail.mailers.smtp.transport'),
                'mail.mailers.smtp.host' => $mailSettings['MAIL_HOST'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $mailSettings['MAIL_PORT'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.encryption' => $mailSettings['MAIL_ENCRYPTION'] ?? config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => $mailSettings['MAIL_USERNAME'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $mailSettings['MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                // 'mail.mailers.smtp.timeout' => $mailSettings->MAIL_TIMEOUT'] ?? config('mail.mailers.smtp.timeout'),
                // 'mail.mailers.smtp.auth_mode' => $mailSettings->MAIL_AUTH_MODE'] ?? config('mail.mailers.smtp.auth_mode'),
            ]);
        }
        // dd(config('mail.mailers'));
    }
}
