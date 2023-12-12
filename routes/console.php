<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\AccountGroupSeeder;
use Illuminate\Database\Schema\Blueprint;

require_once base_path('dev/db.php');

Artisan::command('dev:m', function () {
    // Schema::table('users', function (Blueprint $table) {
    //     $table->ipAddress()->nullable();
    // });

    Schema::create('branch_settings', function (Blueprint $table) {
        $table->id();

        $table->string('key')->nullable();
        $table->string('value')->nullable();
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->unsignedBigInteger('add_sale_invoice_layout_id')->nullable();
        $table->unsignedBigInteger('pos_sale_invoice_layout_id')->nullable();

        $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        $table->foreign('add_sale_invoice_layout_id')->references('id')->on('invoice_layouts')->onDelete('set null');
        $table->foreign('pos_sale_invoice_layout_id')->references('id')->on('invoice_layouts')->onDelete('set null');
    });
});

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});

Artisan::command('dev:seed', function () {
    (new AccountGroupSeeder)->run();
});
