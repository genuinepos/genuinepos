<?php

use App\Models\Tenant;
use App\Models\GeneralSetting;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

Route::get('my-test', function () {

    // $branchId = auth()->check() ? auth()->user()->branch_id : null;
    // $tenantId = tenant('id');  // Ensure this retrieves the current tenant's ID
    // $cacheKey = "{$tenantId}_generalSettings_{$branchId}";

    // Log::info("Checking cache for key: $cacheKey");

    // return Cache::rememberForever($cacheKey, function () use ($branchId, $cacheKey) {
    //     Log::info("Cache miss for key: $cacheKey, querying database");
    //     $settings = GeneralSetting::where('branch_id', $branchId)
    //         ->orWhereIn('key', [
    //             'business_or_shop__business_name',
    //             'business_or_shop__business_logo',
    //             'business_or_shop__address',
    //             'business_or_shop__email',
    //             'business_or_shop__phone',
    //         ])->pluck('value', 'key')->toArray();

    //     Log::info("Storing data in cache for key: $cacheKey");
    //     return $settings;
    // });

    $database = 'pos';

    // Get connection details
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');
    $host = config('database.connections.mysql.host');

    // Check if the database exists
  
        $pdo = new PDO("mysql:host={$host};dbname=information_schema", $username, $password);
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM SCHEMATA WHERE SCHEMA_NAME = :database");
        $stmt->bindParam(':database', $database);
        $stmt->execute();
        $dbExists = $stmt->fetchColumn();

        if ($dbExists) {
           dd($dbExists);
        } else {
            dd('NO');
        }

});

Route::get('t-id', function () {
});
