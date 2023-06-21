<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

Artisan::command('init', function() {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});