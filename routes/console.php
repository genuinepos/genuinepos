<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('truncate', function () {
    $this->comment('Truncating all tables');
     try {
         Schema::disableForeignKeyConstraints();
         $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        foreach ($tables as $key => $table) {
            $str = sprintf("%-4s --> Truncated table = `$table`", $key);
            $this->info($str);
            DB::table($table)->truncate();
            DB::statement("ALTER TABLE `$table` AUTO_INCREMENT=1");
        }
     } catch(Exception $e){
         $this->comment('Error !');
         $this->comment($e->getMessage());
    } finally {
        Schema::enableForeignKeyConstraints();
    }
})->purpose('Truncate all tables in database');

Artisan::command('reset', function() {
    Artisan::call('truncate');
    Artisan::call('db:seed');
});

