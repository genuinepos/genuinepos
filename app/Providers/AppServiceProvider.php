<?php

namespace App\Providers;

use Exception;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            // $generalSettings = DB::table('general_settings')->first();
            $generalSettings = GeneralSetting::first()->toArray();
            // dd($generalSettings);
            $addons = DB::table('addons')->first();
            // $warehouseCount = DB::table('warehouses')->count();
            $dateFormat = $generalSettings['business']['date_format'];
            $__date_format = str_replace('-', '/', $dateFormat);
            if (isset($generalSettings) && isset($addons)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('addons', $addons);
                // view()->share('warehouseCount', $warehouseCount);
                view()->share('__date_format', $__date_format);
            }

            // $mailSettings = GeneralSetting::email();
            $mailSettings =  $generalSettings['email_setting'];
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
        } catch(Exception $e) {}
    }
}
