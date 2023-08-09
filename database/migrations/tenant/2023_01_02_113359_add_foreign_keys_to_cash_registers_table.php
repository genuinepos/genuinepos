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
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['cash_counter_id'])->references(['id'])->on('cash_counters')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
            $table->dropForeign('cash_registers_admin_id_foreign');
            $table->dropForeign('cash_registers_branch_id_foreign');
            $table->dropForeign('cash_registers_cash_counter_id_foreign');
            $table->dropForeign('cash_registers_sale_account_id_foreign');
        });
    }
};
