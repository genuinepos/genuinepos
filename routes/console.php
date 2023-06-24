<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});
