<?php

namespace App\Providers;

use Exception;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // The application will send a exception(warning) message. But will work.
        try {
            $generalSettings = DB::table('general_settings')->first();
            $addons = DB::table('addons')->first();
            if (isset($generalSettings) && isset($addons)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('addons', $addons);
            }
        } catch (Exception $e) {
            echo 'General setting is important! ' . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
