<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeNewColsPurchaseProductsTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->unsignedBigInteger('production_id')->nullable();
            $table->unsignedBigInteger('opening_stock_id')->nullable();
            $table->foreign('production_id')->references('id')->on('purchase_products')->onDelete('cascade');
            $table->foreign('opening_stock_id')->references('id')->on('product_opening_stock_id')->onDelete('cascade');
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
            //
        });
    }
}
