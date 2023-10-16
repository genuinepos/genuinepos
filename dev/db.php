<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;


/**
 * Path updates of DB after the App goes to production.
 */
Artisan::command('patch:001', function() {
   echo 'Patching...' . PHP_EOL;
   Schema::create('', function (Blueprint $table) {
        // production updats goes here
   });
});
