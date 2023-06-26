<?php

namespace App\Listeners;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class TenantBootstrapped
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {

        try {
            Cache::rememberForever('generalSettings', function () {
                return GeneralSetting::where('branch_id', auth()->user()?->branch_id ?? null)->pluck('value', 'key')->toArray();
            });
            $generalSettings = Cache::get('generalSettings') ?? GeneralSetting::where('branch_id', auth()->user()?->branch_id ?? null)->pluck('value', 'key')->toArray();
            config([
                'generalSettings' => $generalSettings,
                'mail.mailers.smtp.transport' => $generalSettings['email_config__MAIL_MAILER'] ?? config('mail.mailers.smtp.transport'),
                'mail.mailers.smtp.host' => $generalSettings['email_config__MAIL_HOST'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $generalSettings['email_config__MAIL_PORT'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.encryption' => $generalSettings['email_config__MAIL_ENCRYPTION'] ?? config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => $generalSettings['email_config__MAIL_USERNAME'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $generalSettings['email_config__MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                // 'mail.mailers.smtp.timeout' => $generalSettings['email_config__MAIL_TIMEOUT'] ?? config('mail.mailers.smtp.timeout'),
                // 'mail.mailers.smtp.auth_mode' => $generalSettings['email_config__MAIL_AUTH_MODE'] ?? config('mail.mailers.smtp.auth_mode'),
            ]);

            $dateFormat = $generalSettings['business__date_format'];
            $__date_format = str_replace('-', '/', $dateFormat);
            if (isset($generalSettings)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('__date_format', $__date_format);
            }
        } catch (Exception $e) {
        }
    }
}
