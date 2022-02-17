<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyToCashRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
            $table->decimal('cash_in_hand', 22, 2)->after('admin_id')->default(0);
            $table->string('date', 20)->after('cash_in_hand')->nullable();
            $table->dropColumn('total_card_slips');
            $table->dropColumn('total_cheques');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            //
        });
    }
}
