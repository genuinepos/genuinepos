<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTransferStockToWarehouseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_stock_to_warehouse_products', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['transfer_stock_id'])->references(['id'])->on('transfer_stock_to_warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_stock_to_warehouse_products', function (Blueprint $table) {
            $table->dropForeign('transfer_stock_to_warehouse_products_product_id_foreign');
            $table->dropForeign('transfer_stock_to_warehouse_products_product_variant_id_foreign');
            $table->dropForeign('transfer_stock_to_warehouse_products_transfer_stock_id_foreign');
        });
    }
}
