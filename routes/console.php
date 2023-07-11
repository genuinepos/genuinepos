<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Modules\SAAS\Enums\UserType;

Artisan::command('init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});

Artisan::command('inspire', function () {
    $q = Inspiring::quote();
    $this->comment($q);
});

Artisan::command('temp', function () {
    Schema::table('users', function (Blueprint $table) {
        $table->enum('user_type', array_column(UserType::cases(), 'value'))->default(UserType::Customer->value)->after('id');
    });
});
