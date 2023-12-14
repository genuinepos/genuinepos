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


    Schema::create('hrm_leave_types', function (Blueprint $table) {
        $table->id('id');
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->string('type');
        $table->integer('max_leave_count');
        $table->integer('leave_count_interval');
        $table->timestamps();
        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('cascade');
    });

    Schema::create('hrm_leaves', function (Blueprint $table) {
        $table->id('id');
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->string('leave_no', 191)->nullable();
        $table->unsignedBigInteger('leave_type_id')->nullable();
        $table->unsignedBigInteger('user_id')->index('hrm_leaves_user_id_foreign');
        $table->string('start_date')->nullable();
        $table->string('end_date')->nullable();
        $table->text('reason')->nullable();
        $table->tinyInteger('status');
        $table->unsignedBigInteger('created_by_id')->index('hrm_leaves_created_by_id_foreign');
        $table->timestamps();

        $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        $table->foreign('leave_type_id')->references('id')->on('hrm_leave_types')->onDelete('set null');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
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
