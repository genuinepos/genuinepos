<?php

use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

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

    Schema::create('purchase_returns', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('voucher_no');
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->unsignedBigInteger('purchase_id')->nullable();
        $table->unsignedBigInteger('supplier_account_id')->nullable();
        $table->unsignedBigInteger('purchase_account_id')->nullable();
        $table->decimal('total_item', 22, 2)->default(0);
        $table->decimal('total_qty', 22, 2)->default(0);
        $table->decimal('net_total_amount', 22, 2)->default(0);
        $table->decimal('return_discount', 22, 2)->default(0);
        $table->decimal('return_discount_type', 22, 2)->default(0);
        $table->decimal('return_discount_amount', 22, 2)->default(0);
        $table->unsignedBigInteger('return_tax_ac_id')->nullable();
        $table->decimal('return_tax_percent', 22, 2)->default(0);
        $table->tinyInteger('return_tax_type')->default(1);
        $table->decimal('return_tax_amount', 22, 2)->default(0);
        $table->decimal('total_return_amount', 22, 2)->default(0);
        $table->decimal('due', 22, 2)->default(0);
        $table->decimal('received_amount', 22, 2)->default(0);
        $table->string('date')->nullable();
        $table->timestamp('date_ts')->nullable();
        $table->text('note')->nullable();
        $table->unsignedBigInteger('created_by_id')->nullable();
        $table->timestamps();

        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
        $table->foreign(['supplier_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        $table->foreign(['purchase_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        $table->foreign(['return_tax_ac_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
    });

    Schema::create('purchase_return_products', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->unsignedBigInteger('warehouse_id')->nullable();
        $table->unsignedBigInteger('purchase_return_id');
        $table->unsignedBigInteger('purchase_product_id')->nullable()->comment('this_field_only_for_purchase_invoice_return.');
        $table->unsignedBigInteger('product_id')->nullable();
        $table->unsignedBigInteger('variant_id')->nullable();
        $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
        $table->decimal('return_qty', 22, 2)->default(0);
        $table->decimal('purchased_qty', 22, 2)->default(0);
        $table->unsignedBigInteger('unit_id')->nullable();
        $table->decimal('unit_discount', 22, 2)->default(0);
        $table->tinyInteger('unit_discount_type')->default(1);
        $table->decimal('unit_discount_amount', 22, 2)->default(0);
        $table->unsignedBigInteger('tax_ac_id')->nullable();
        $table->tinyInteger('unit_tax_type')->default(1);
        $table->decimal('unit_tax_percent', 22, 2)->default(0);
        $table->decimal('unit_tax_amount', 22, 2)->default(0);
        $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
        $table->decimal('return_subtotal', 22)->default(0);
        $table->boolean('is_delete_in_update')->default(false);
        $table->timestamps();

        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
        $table->foreign(['purchase_return_id'])->references(['id'])->on('purchase_returns')->onDelete('CASCADE');
        $table->foreign(['purchase_product_id'])->references(['id'])->on('purchase_products')->onDelete('CASCADE');
        $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
        $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
        $table->foreign(['tax_ac_id'])->references(['id'])->on('product_variants')->onDelete('SET NULL');
    });
});
