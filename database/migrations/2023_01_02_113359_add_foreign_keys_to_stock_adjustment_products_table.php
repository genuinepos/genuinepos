<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStockAdjustmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustment_products', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['stock_adjustment_id'])->references(['id'])->on('stock_adjustments')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustment_products', function (Blueprint $table) {
            $table->dropForeign('stock_adjustment_products_product_id_foreign');
            $table->dropForeign('stock_adjustment_products_product_variant_id_foreign');
            $table->dropForeign('stock_adjustment_products_stock_adjustment_id_foreign');
        });
    }
}
