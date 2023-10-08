<?php

use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;


Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});

Artisan::command('dev:m', function () {

    // 1
    // Schema::table('account_ledgers', function (Blueprint $table) {

    //     $table->dropColumn('accounting_voucher_description_id');
    //     $table->unsignedBigInteger('voucher_description_id')->after('loan_payment_id')->nullable();

    //     $table->foreign('voucher_description_id')->references('id')->on('accounting_voucher_descriptions')->onDelete('cascade');
    // });
});

Artisan::command('play', function() {
    $notifiable = \App\Models\User::find(3);
    $res =  \URL::temporarySignedRoute(
        'saas.verification.verify',
        Carbon::now()->addMinutes(\Config::get('auth.verification.expire', 60)),
        [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ]
    );
    dd($res);
});