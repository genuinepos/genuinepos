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


Artisan::command('a', function (CodeGenerationService $s) {

    $res1 = $s->generateAndTypeWiseWithoutYearMonth('contacts', 'contact_id', 'type', '1', 'C');
    $res2 = $s->generateAndTypeWiseWithoutYearMonth('contacts', 'contact_id', 'type', '2', 'S');
    dd($res1, $res2, PHP_INT_MAX);
});

// Just merged this line of text.
