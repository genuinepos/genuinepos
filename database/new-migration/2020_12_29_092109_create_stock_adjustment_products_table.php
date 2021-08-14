<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_adjustment_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('stock_adjustment_id')->references('id')->on('stock_adjustments')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustment_products');
    }
}
