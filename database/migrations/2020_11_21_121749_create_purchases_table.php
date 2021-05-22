<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->tinyInteger('pay_term')->nullable();
            $table->bigInteger('pay_term_number')->nullable();
            $table->bigInteger('total_item');
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('order_discount', 22, 2)->default(0.00);
            $table->tinyInteger('order_discount_type')->default(1);
            $table->decimal('order_discount_amount', 22, 2)->default(0.00);
            $table->string('shipment_details')->nullable();
            $table->decimal('shipment_charge', 22, 2)->default(0.00);
            $table->mediumText('purchase_note')->nullable();
            $table->decimal('purchase_tax_percent', 22, 2)->default(0.00);
            $table->decimal('purchase_tax_amount', 22, 2)->default(0.00);
            $table->decimal('total_purchase_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->decimal('purchase_return_amount', 22, 2)->default(0.00);
            $table->decimal('purchase_return_due', 22, 2)->default(0.00);
            $table->mediumText('payment_note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->tinyInteger('purchase_status')->default(1);
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_last_created')->default(0);
            $table->boolean('is_return_available')->default(0);
            $table->string('attachment')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
