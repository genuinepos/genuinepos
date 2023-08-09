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
        Schema::table('purchase_order_product_receives', function (Blueprint $table) {
            $table->foreign(['order_product_id'])->references(['id'])->on('purchase_order_products')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_product_receives', function (Blueprint $table) {
            $table->dropForeign('purchase_order_product_receives_order_product_id_foreign');
        });
    }
};
