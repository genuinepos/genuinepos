<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToWarehouseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_to_warehouse_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transfer_stock_id')->index('transfer_stock_to_warehouse_products_transfer_stock_id_foreign');
            $table->unsignedBigInteger('product_id')->index('transfer_stock_to_warehouse_products_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('transfer_stock_to_warehouse_products_product_variant_id_foreign');
            $table->decimal('unit_price', 22);
            $table->decimal('quantity', 22);
            $table->decimal('received_qty', 22)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('subtotal', 22);
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
        Schema::dropIfExists('transfer_stock_to_warehouse_products');
    }
}
