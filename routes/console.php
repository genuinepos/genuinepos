<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});
