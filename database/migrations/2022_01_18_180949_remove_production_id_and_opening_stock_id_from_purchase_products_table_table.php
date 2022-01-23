<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductionIdAndOpeningStockIdFromPurchaseProductsTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->dropForeign(['production_id']);
            $table->dropColumn('production_id');
            $table->dropColumn('opening_stock_id');
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
