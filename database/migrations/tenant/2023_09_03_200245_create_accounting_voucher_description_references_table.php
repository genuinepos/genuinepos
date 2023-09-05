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
        Schema::create('voucher_description_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_description_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->unsignedBigInteger('stock_adjustment_id')->nullable();
            $table->decimal('amount', 22, 2)->default(0);
            $table->timestamps();

            $table->foreign('voucher_description_id')->references('id')->on('accounting_voucher_descriptions')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->onDelete('cascade');
            $table->foreign('stock_adjustment_id')->references('id')->on('stock_adjustments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_voucher_description_references');
    }
};
