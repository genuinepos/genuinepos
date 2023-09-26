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

    Schema::create('sales', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->tinyInteger('status')->default(1)->comment('1=final;2=draft;3=order;4=quotation;5=hold;6=suspended');
        $table->string('invoice_id', 100)->nullable();
        $table->string('order_id', 100)->nullable();
        $table->string('quotation_id', 100)->nullable();
        $table->string('draft_id', 100)->nullable();
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->unsignedBigInteger('customer_account_id')->nullable();
        $table->unsignedBigInteger('sale_account_id')->nullable();
        $table->tinyInteger('pay_term')->nullable();
        $table->bigInteger('pay_term_number')->nullable();
        $table->decimal('total_item')->default(0);
        $table->decimal('total_qty', 22, 2)->default(0);
        $table->decimal('total_sold_qty', 22, 2)->default(0);
        $table->decimal('total_quotation_qty', 22, 2)->default(0);
        $table->decimal('total_ordered_qty', 22, 2)->default(0);
        $table->decimal('total_delivered_qty', 22, 2)->default(0);
        $table->decimal('total_left_qty')->default(0);
        $table->decimal('net_total_amount', 22, 2)->default(0);
        $table->tinyInteger('order_discount_type')->default(1);
        $table->decimal('order_discount', 22, 2)->default(0);
        $table->decimal('order_discount_amount', 22, 2)->default(0);
        $table->decimal('redeem_point', 22, 2)->default(0);
        $table->decimal('redeem_point_rate', 22, 2)->default(0);
        $table->string('shipment_details')->nullable();
        $table->mediumText('shipment_address')->nullable();
        $table->decimal('shipment_charge', 22, 2)->default(0);
        $table->tinyInteger('shipment_status')->default(0);
        $table->mediumText('delivered_to')->nullable();
        $table->mediumText('note')->nullable();
        $table->unsignedBigInteger('sale_tax_ac_id')->nullable();
        $table->decimal('order_tax_percent', 22, 2)->default(0);
        $table->decimal('order_tax_amount', 22, 2)->default(0);
        $table->decimal('total_invoice_amount', 22, 2)->default(0);
        $table->decimal('paid', 22, 2)->default(0);
        $table->decimal('change_amount', 22, 2)->default(0);
        $table->decimal('due', 22, 2)->default(0);
        $table->boolean('is_return_available')->default(false);

        $table->boolean('exchange_status')->default(false)->comment('0=not_exchanged,1=exchanged');
        $table->decimal('sale_return_amount', 22, 2)->default(0);
        $table->string('date', 191)->nullable();
        $table->timestamp('date_ts')->nullable();
        $table->timestamp('sale_date_ts')->nullable();
        $table->timestamp('quotation_date_ts')->nullable();
        $table->timestamp('order_date_ts')->nullable();
        $table->timestamp('draft_date_ts')->nullable();
        $table->boolean('quotation_status')->default(0);
        $table->boolean('order_status')->default(0);
        $table->boolean('draft_status')->default(0);
        $table->tinyInteger('sale_screen')->default(1)->comment('1=add_sale;2=pos');
        $table->unsignedBigInteger('sales_order_id')->nullable();
        $table->unsignedBigInteger('created_by_id')->nullable();
        $table->timestamps();

        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['sale_tax_ac_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
        $table->foreign(['customer_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
        $table->foreign(['sales_order_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
        $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
    });

    Schema::create('sale_products', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('sale_id');
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('variant_id')->nullable();
        $table->unsignedBigInteger('branch_id')->nullable();
        $table->unsignedBigInteger('warehouse_id')->nullable();
        $table->decimal('quantity', 22, 2)->default(0);
        $table->decimal('ordered_quantity', 22, 2)->default(0);
        $table->decimal('delivered_quantity', 22, 2)->default(0);
        $table->decimal('left_quantity', 22, 2)->default(0);
        $table->unsignedBigInteger('unit_id')->nullable();
        $table->tinyInteger('unit_discount_type')->default(1);
        $table->decimal('unit_discount', 22, 2)->default(0);
        $table->decimal('unit_discount_amount', 22, 2)->default(0);
        $table->unsignedBigInteger('tax_ac_id')->nullable();
        $table->decimal('unit_tax_percent', 22, 2)->default(0);
        $table->decimal('unit_tax_amount', 22, 2)->default(0);
        $table->decimal('unit_cost_inc_tax', 22, 2)->default(0)->comment('this_col_for_invoice_profit_report');
        $table->decimal('unit_price_exc_tax', 22, 2)->default(0);
        $table->decimal('unit_price_inc_tax', 22, 2)->default(0);
        $table->decimal('subtotal', 22, 2)->default(0);
        $table->mediumText('description')->nullable();
        $table->decimal('ex_quantity', 22, 2)->default(0);
        $table->tinyInteger('ex_status')->default(0)->comment('0=no_exchanged,1=prepare_to_exchange,2=exchanged');
        $table->boolean('is_delete_in_update')->default(0);
        $table->timestamps();

        $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
        $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
        $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
        $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
    });
});
