<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->default(1)->comment('1=final;2=draft;3=order;4=quotation;5=hold;6=suspended');
            $table->string('invoice_id', 255)->nullable();
            $table->string('order_id', 255)->nullable();
            $table->string('quotation_id', 255)->nullable();
            $table->string('draft_id', 255)->nullable();
            $table->string('hold_invoice_id', 255)->nullable();
            $table->string('suspend_id', 255)->nullable();
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
            $table->decimal('earned_point', 22, 2)->default(0);
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
