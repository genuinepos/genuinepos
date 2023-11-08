<?php

use Illuminate\Support\Facades\Artisan;

require_once base_path('dev/db.php');

Artisan::command('dev:m', function () {

    Schema::table('purchase_products', function (Blueprint $table) {

        $table->unsignedBigInteger('transfer_stock_product_id')->index('purchase_products_transfer_stock_product_id_foreign');
        $table->foreign(['transfer_stock_product_id'])->references(['id'])->on('transfer_stock_products')->onDelete('CASCADE');
    });
});

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});
