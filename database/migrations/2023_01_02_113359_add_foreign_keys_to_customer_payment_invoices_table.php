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
        Schema::table('customer_payment_invoices', function (Blueprint $table) {
            $table->foreign(['customer_payment_id'])->references(['id'])->on('customer_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_return_id'])->references(['id'])->on('sale_returns')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_payment_invoices', function (Blueprint $table) {
            $table->dropForeign('customer_payment_invoices_customer_payment_id_foreign');
            $table->dropForeign('customer_payment_invoices_sale_id_foreign');
            $table->dropForeign('customer_payment_invoices_sale_return_id_foreign');
        });
    }
};
