<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToSalesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_return_account_id')->after('branch_id')->nullable();
            $table->foreign('sale_return_account_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            //
        });
    }
}
