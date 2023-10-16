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
        Schema::create('product_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->tinyInteger('voucher_type');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('sale_product_id')->nullable();
            $table->unsignedBigInteger('sale_return_product_id')->nullable();
            $table->unsignedBigInteger('purchase_product_id')->nullable();
            $table->unsignedBigInteger('purchase_return_product_id')->nullable();
            $table->unsignedBigInteger('opening_stock_product_id')->nullable();
            $table->unsignedBigInteger('stock_adjustment_product_id')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->unsignedBigInteger('production_ingredient_id')->nullable();
            $table->unsignedBigInteger('transfer_stock_product_id')->nullable();
            $table->decimal('rate', 22, 2)->default(0);
            $table->decimal('in', 22, 2)->default(0);
            $table->decimal('out', 22, 2)->default(0);
            $table->decimal('subtotal', 22, 2)->default(0);
            $table->string('type', 5);
            $table->string('date', 255)->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('sale_product_id')->references('id')->on('sale_products')->onDelete('cascade');
            $table->foreign('purchase_product_id')->references('id')->on('purchase_products')->onDelete('cascade');
            $table->foreign('purchase_return_product_id')->references('id')->on('purchase_return_products')->onDelete('cascade');
            $table->foreign('opening_stock_product_id')->references('id')->on('product_opening_stocks')->onDelete('cascade');
            $table->foreign('stock_adjustment_product_id')->references('id')->on('stock_adjustment_products')->onDelete('cascade');
            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('production_ingredient_id')->references('id')->on('production_ingredients')->onDelete('cascade');
            $table->foreign('transfer_stock_product_id')->references('id')->on('transfer_stock_products')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ledgers');
    }
};
