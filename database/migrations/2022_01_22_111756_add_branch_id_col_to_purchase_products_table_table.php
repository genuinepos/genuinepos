<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdColToPurchaseProductsTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id')->nullable()->comment('This column for track branch wise FIFO/LIFO method.');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
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
