<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPriceGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_group_products', function (Blueprint $table) {
            $table->foreign(['price_group_id'])->references(['id'])->on('price_groups')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_group_products', function (Blueprint $table) {
            $table->dropForeign('price_group_products_price_group_id_foreign');
            $table->dropForeign('price_group_products_product_id_foreign');
            $table->dropForeign('price_group_products_variant_id_foreign');
        });
    }
}
