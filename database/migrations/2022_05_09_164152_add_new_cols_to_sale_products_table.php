<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColsToSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            
            $table->unsignedBigInteger('stock_branch_id')->nullable();
            $table->unsignedBigInteger('stock_warehouse_id')->nullable();
   
            $table->foreign('stock_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('stock_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_products', function (Blueprint $table) {
            //
        });
    }
}
