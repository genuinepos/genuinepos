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
        Schema::table('discount_products', function (Blueprint $table) {
            $table->foreign(['discount_id'])->references(['id'])->on('discounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discount_products', function (Blueprint $table) {
            $table->dropForeign('discount_products_discount_id_foreign');
            $table->dropForeign('discount_products_product_id_foreign');
        });
    }
};
