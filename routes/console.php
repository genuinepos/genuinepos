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

    Schema::create('product_opening_stocks', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('branch_id')->nullable()->index('product_opening_stocks_branch_id_foreign');
        $table->unsignedBigInteger('warehouse_id')->nullable()->index('product_opening_stocks_warehouse_id_foreign');
        $table->unsignedBigInteger('product_id')->nullable()->index('product_opening_stocks_product_id_foreign');
        $table->unsignedBigInteger('variant_id')->nullable()->index('product_opening_stocks_variant_id_foreign');
        $table->decimal('unit_cost_inc_tax', 22)->default(0);
        $table->decimal('quantity', 22)->default(0);
        $table->decimal('subtotal', 22)->default(0);
        $table->string('lot_no')->nullable();
        $table->timestamps();

        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
        $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
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
