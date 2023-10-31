<?php

use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

require_once base_path('dev/db.php');

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

    Schema::create('cash_registers', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('sale_account_id')->nullable()->index('cash_registers_sale_account_id_foreign');
        $table->unsignedBigInteger('cash_counter_id')->nullable()->index('cash_registers_cash_counter_id_foreign');
        $table->unsignedBigInteger('cash_account_id')->nullable()->index('cash_registers_cash_account_id_foreign');
        $table->unsignedBigInteger('branch_id')->nullable()->index('cash_registers_branch_id_foreign');
        $table->unsignedBigInteger('user_id')->nullable()->index('cash_registers_user_id_foreign');
        $table->decimal('opening_cash', 22, 2)->default(0);
        $table->string('date', 20)->nullable();
        $table->timestamp('closed_at')->nullable();
        $table->decimal('closing_cash', 22, 2)->default(0);
        $table->boolean('status')->default(true)->comment('1=open;0=closed;');
        $table->text('closing_note')->nullable();
        $table->timestamps();

        $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['cash_counter_id'])->references(['id'])->on('cash_counters')->onDelete('SET NULL');
        $table->foreign(['cash_account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
        $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
    });

    Schema::create('cash_register_transactions', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('cash_register_id')->nullable()->index('cash_register_transactions_cash_register_id_foreign');
        $table->unsignedBigInteger('sale_id')->nullable()->index('cash_register_transactions_sale_id_foreign');
        $table->timestamps();

        $table->foreign(['cash_register_id'])->references(['id'])->on('cash_registers')->onDelete('CASCADE');
        $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
    });
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
});
