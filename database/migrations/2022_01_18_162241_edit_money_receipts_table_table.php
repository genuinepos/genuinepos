<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditMoneyReceiptsTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            //$table->dropForeign(['account_type_id']);
            $table->dropColumn('is_amount');
            $table->dropColumn('is_invoice_id');
            $table->dropColumn('received_amount');
            $table->dropColumn('status');
            $table->dropColumn('is_note');
            $table->dropColumn('year');
            $table->dropColumn('date_ts');
            $table->dropColumn('payment_method');
            $table->boolean('is_customer_name')->after('customer_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_receipts', function (Blueprint $table) {
            //
        });
    }
}
