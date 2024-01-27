<?php

use App\Models\Setups\Branch;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\AccountGroupSeeder;
use Illuminate\Database\Schema\Blueprint;

require_once base_path('deployment/db-migrations/001.php');

Artisan::command('dev:m', function () {
    // Schema::table('users', function (Blueprint $table) {
    //     $table->ipAddress()->nullable();
    // });

    // Schema::create('product_units', function (Blueprint $table) {
    //     $table->id();
    //     $table->unsignedBigInteger('product_id');
    //     $table->unsignedBigInteger('variant_id')->nullable();
    //     $table->unsignedBigInteger('base_unit_id');
    //     $table->decimal('assigned_unit_quantity', 22, 2)->default(0);
    //     $table->decimal('base_unit_multiplier', 22, 2)->default(0);
    //     $table->unsignedBigInteger('assigned_unit_id');
    //     $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
    //     $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
    //     $table->decimal('unit_price_exc_tax', 22, 2)->default(0);
    //     $table->boolean('is_delete_in_update')->default(0);
    //     $table->timestamps();

    //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    //     $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
    //     $table->foreign('base_unit_id')->references('id')->on('units')->onDelete('cascade');
    //     $table->foreign('assigned_unit_id')->references('id')->on('units')->onDelete('cascade');
    // });

    Schema::table('products', function (Blueprint $table) {
        $table->boolean('has_multiple_unit')->after('is_combo')->default(false);
    });
});

Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});

Artisan::command('dev:seed', function () {
    (new AccountGroupSeeder)->run();
});

Artisan::command('t:1', function () {
    $s = GeneralSetting::where('parent_branch_id', auth()->user()?->branch_id)
        ->orWhere('branch_id', auth()->user()?->branch_id)
        ->orWhereNull('branch_id')
        ->distinct('key')
        ->pluck('value', 'key')
        ->toArray();
    dd($s);
});

Artisan::command('t:2', function () {

    $generalSettings = GeneralSetting::where(function ($query) {
        $query->whereNotNull('branch_id')
            ->whereNotNull('parent_branch_id')
            ->orderBy('branch_id')
            ->orderBy('parent_branch_id');
    })
        ->orWhere(function ($query) {
            $query->whereNotNull('branch_id')
                ->whereNull('parent_branch_id')
                ->orderBy('branch_id');
        })
        ->orWhere(function ($query) {
            $query->whereNull('branch_id')
                ->whereNotNull('parent_branch_id')
                ->orderBy('parent_branch_id');
        })
        ->orWhere(function ($query) {
            $query->whereNull('branch_id')
                ->whereNull('parent_branch_id')
                ->orderBy(DB::raw('RAND()')); // Random order for global settings
        })
        ->distinct('key')
        ->pluck('value', 'key');
    // ->get();

    dd($generalSettings);
});
