<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

require_once base_path('dev/db.php');

Artisan::command('dev:m', function () {
    Schema::table('users', function (Blueprint $table) {
        $table->ipAddress()->nullable();
    });
});

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});
