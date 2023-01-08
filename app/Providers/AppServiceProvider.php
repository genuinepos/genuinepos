<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Services\CacheService;
use App\Services\CacheServiceInterface;
use Exception;
use Illuminate\Support\Facades\Cache;
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
        $this->app->singleton(GeneralSetting::class, function () {
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
            // Cache::forget('generalSettings');
            Cache::rememberForever('generalSettings', function () {
                return GeneralSetting::where('branch_id', (auth()?->user()?->branch_id ?? null))->pluck('value', 'key')->toArray();
            });
            $generalSettings = config('generalSettings') ?? GeneralSetting::where('branch_id', (auth()?->user()?->branch_id ?? null))->pluck('value', 'key')->toArray();
            config([
                'generalSettings' => $generalSettings,
                'mail.mailers.smtp.transport' => $generalSettings['email_setting__MAIL_MAILER'] ?? config('mail.mailers.smtp.transport'),
                'mail.mailers.smtp.host' => $generalSettings['email_setting__MAIL_HOST'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $generalSettings['email_setting__MAIL_PORT'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.encryption' => $generalSettings['email_setting__MAIL_ENCRYPTION'] ?? config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => $generalSettings['email_setting__MAIL_USERNAME'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $generalSettings['email_setting__MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                // 'mail.mailers.smtp.timeout' => $generalSettings->['email_setting__MAIL_TIMEOUT'] ?? config('mail.mailers.smtp.timeout'),
                // 'mail.mailers.smtp.auth_mode' => $generalSettings->['email_setting__MAIL_AUTH_MODE'] ?? config('mail.mailers.smtp.auth_mode'),
            ]);

            $addons = DB::table('addons')->first();
            $dateFormat = $generalSettings['business__date_format'];
            $__date_format = str_replace('-', '/', $dateFormat);
            if (isset($generalSettings) && isset($addons)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('addons', $addons);
                // view()->share('warehouseCount', $warehouseCount);
                view()->share('__date_format', $__date_format);
            }
        } catch (Exception $e) {}
    }
}
