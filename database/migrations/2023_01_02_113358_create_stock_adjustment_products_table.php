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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_adjustment_id')->index('stock_adjustment_products_stock_adjustment_id_foreign');
            $table->unsignedBigInteger('product_id')->index('stock_adjustment_products_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('stock_adjustment_products_product_variant_id_foreign');
            $table->decimal('quantity', 22)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
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
        Schema::dropIfExists('stock_adjustment_products');
    }
}
