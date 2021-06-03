<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->tinyInteger('pay_term')->nullable();
            $table->bigInteger('pay_term_number')->nullable();
            $table->bigInteger('total_item');
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->tinyInteger('order_discount_type')->default(1);
            $table->decimal('order_discount', 22, 2)->default(0.00);
            $table->decimal('order_discount_amount', 22, 2)->default(0.00);
            $table->string('shipment_details')->nullable();
            $table->mediumText('shipment_address')->nullable();
            $table->decimal('shipment_charge', 22, 2)->default(0.00);
            $table->tinyInteger('shipment_status')->nullable();
            $table->mediumText('delivered_to')->nullable();
            $table->mediumText('sale_note')->nullable();
            $table->decimal('order_tax_percent', 22, 2)->default(0.00);
            $table->decimal('order_tax_amount', 22, 2)->default(0.00);
            $table->decimal('total_payable_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('change_amount', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->boolean('is_return_available')->default(0);
            $table->decimal('sale_return_amount', 22, 2)->default(0.00);
            $table->decimal('sale_return_due', 22, 2)->default(0.00);
            $table->mediumText('payment_note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=final;2=draft;3=challan;4=quatation;5=hold');
            $table->boolean('is_fixed_challen')->default(0);
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_return_available')->default(0);
            $table->boolean('ex_status')->default(0)->comment('0=exchangeed,1=exchanged');
            $table->string('attachment')->nullable();
            $table->tinyInteger('created_by')->default(1)->comment('1=add_sale;2=pos');
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
}
