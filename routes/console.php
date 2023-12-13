<?php

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
