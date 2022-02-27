<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToTransferStockBranchToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_stock_branch_to_branches', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id')->after('bank_account_id')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_stock_branch_to_branches', function (Blueprint $table) {
            //
        });
    }
}
