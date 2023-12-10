<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branch_settings', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('branch_id');
            // $table->string('invoice_prefix')->nullable();
            // $table->string('quotation_prefix')->nullable();
            // $table->string('sales_order_prefix')->nullable();
            // $table->string('sales_return_prefix')->nullable();
            // $table->string('payment_voucher_prefix')->nullable();
            // $table->string('receipt_voucher_prefix')->nullable();
            // $table->string('purchase_invoice_prefix')->nullable();
            // $table->string('purchase_order_prefix')->nullable();
            // $table->string('purchase_return_prefix')->nullable();
            // $table->string('stock_adjustment_prefix')->nullable();
            // $table->unsignedBigInteger('add_sale_invoice_layout_id')->nullable();
            // $table->unsignedBigInteger('pos_sale_invoice_layout_id')->nullable();
            // $table->unsignedBigInteger('default_tax_ac_id')->nullable();
            // $table->timestamps();

            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            // $table->foreign('add_sale_invoice_layout_id')->references('id')->on('invoice_layouts')->onDelete('set null');
            // $table->foreign('pos_sale_invoice_layout_id')->references('id')->on('invoice_layouts')->onDelete('set null');
            // $table->foreign('default_tax_ac_id')->references('id')->on('accounts')->onDelete('set null');

            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('add_sale_invoice_layout_id')->nullable();
            $table->unsignedBigInteger('pos_sale_invoice_layout_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_settings');
    }
};
