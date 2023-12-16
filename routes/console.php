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


    Schema::create('hrm_attendances', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('branch_id')->index('hrm_attendances_branch_id_foreign')->nullable();
        $table->string('clock_in_date');
        $table->string('clock_out_date')->nullable();
        $table->unsignedBigInteger('user_id')->index('hrm_attendances_user_id_foreign');
        $table->string('clock_in')->nullable();
        $table->string('clock_out')->nullable();
        $table->string('work_duration')->nullable();
        $table->text('clock_in_note')->nullable();
        $table->text('clock_out_note')->nullable();
        $table->string('month')->nullable();
        $table->string('year')->nullable();
        $table->timestamp('clock_in_ts')->nullable();
        $table->timestamp('clock_out_ts')->nullable();
        $table->timestamp('at_date_ts')->nullable();
        $table->boolean('is_completed')->default(false);
        $table->unsignedBigInteger('shift_id')->nullable();
        $table->timestamps();

        $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['shift_id'])->references(['id'])->on('hrm_shifts')->onDelete('SET NULL');
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
