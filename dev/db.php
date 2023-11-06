<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

/**
 * Path updates of DB after the App goes to production.
 */
Artisan::command('patch:001', function () {
    echo 'Patching...'.PHP_EOL;
    Schema::create('', function (Blueprint $table) {
        // production updats goes here
    });
});
