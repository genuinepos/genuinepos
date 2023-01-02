<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPaymentMethodSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_settings', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_settings', function (Blueprint $table) {
            $table->dropForeign('payment_method_settings_account_id_foreign');
            $table->dropForeign('payment_method_settings_branch_id_foreign');
            $table->dropForeign('payment_method_settings_payment_method_id_foreign');
        });
    }
}
