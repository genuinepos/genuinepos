<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['opening_stock_id'])->references(['id'])->on('product_opening_stocks')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['production_id'])->references(['id'])->on('productions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_order_product_id'])->references(['id'])->on('purchase_order_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_return_product_id'])->references(['id'])->on('sale_return_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['transfer_branch_to_branch_product_id'])->references(['id'])->on('transfer_stock_branch_to_branch_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->dropForeign('purchase_products_branch_id_foreign');
            $table->dropForeign('purchase_products_opening_stock_id_foreign');
            $table->dropForeign('purchase_products_production_id_foreign');
            $table->dropForeign('purchase_products_product_id_foreign');
            $table->dropForeign('purchase_products_product_order_product_id_foreign');
            $table->dropForeign('purchase_products_product_variant_id_foreign');
            $table->dropForeign('purchase_products_purchase_id_foreign');
            $table->dropForeign('purchase_products_sale_return_product_id_foreign');
            $table->dropForeign('purchase_products_transfer_branch_to_branch_product_id_foreign');
        });
    }
};
