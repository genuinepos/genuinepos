<?php

namespace App\Providers;

use Exception;
use App\Models\GeneralSetting;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheServiceInterface;
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
        $this->app->singleton(GeneralSetting::class, function() {
            return new GeneralSetting();
        });
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->alias(GeneralSetting::class, 'general-settings');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            // $generalSettings = \Cache::get('generalSettings');
            // $generalSettings = GeneralSetting::first()->toArray();
            Cache::rememberForever('generalSettings', function() {
                return GeneralSetting::first()->toArray();
            });

            $generalSettings = \Cache::get('generalSettings') ?? GeneralSetting::first()->toArray();
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
