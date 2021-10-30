<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Utils\DatabaseUtils\TimestampType;
// use Doctrine\DBAL\Types\Type;

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
        // The application will send a exception(warning) message if anything goes wrong. But will work.
        try {
            $generalSettings = DB::table('general_settings')->first();
            $addons = DB::table('addons')->first();
            $warehouseCount = DB::table('warehouses')->count();
            if (isset($generalSettings) && isset($addons) && isset($warehouseCount)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('addons', $addons);
                view()->share('warehouseCount', $warehouseCount);
            }
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
