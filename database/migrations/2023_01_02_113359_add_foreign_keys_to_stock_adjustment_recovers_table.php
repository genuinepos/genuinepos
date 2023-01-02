<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStockAdjustmentRecoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustment_recovers', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['stock_adjustment_id'])->references(['id'])->on('stock_adjustments')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustment_recovers', function (Blueprint $table) {
            $table->dropForeign('stock_adjustment_recovers_account_id_foreign');
            $table->dropForeign('stock_adjustment_recovers_payment_method_id_foreign');
            $table->dropForeign('stock_adjustment_recovers_stock_adjustment_id_foreign');
        });
    }
}
