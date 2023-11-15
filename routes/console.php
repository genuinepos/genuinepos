<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

require_once base_path('dev/db.php');

Artisan::command('dev:m', function () {

    Schema::table('cash_register_transactions', function (Blueprint $table) {

        $table->unsignedBigInteger('sale_ref_id')->nullable()->after('voucher_description_id')->index('cash_register_transactions_sale_ref_id_foreign');
        $table->foreign(['sale_ref_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
    });
});

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});
