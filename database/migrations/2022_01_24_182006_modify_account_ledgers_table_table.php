<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAccountLedgersTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->timestamp('date')->change()->nullable()->default(NULL);
            $table->unsignedBigInteger('branch_id')->after('id')->nullable();
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
        Schema::table('account_ledgers', function (Blueprint $table) {
            //
        });
    }
}
