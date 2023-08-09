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
        Schema::table('purchase_payments', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['supplier_payment_id'])->references(['id'])->on('supplier_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['supplier_return_id'])->references(['id'])->on('purchase_returns')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_payments', function (Blueprint $table) {
            $table->dropForeign('purchase_payments_account_id_foreign');
            $table->dropForeign('purchase_payments_branch_id_foreign');
            $table->dropForeign('purchase_payments_payment_method_id_foreign');
            $table->dropForeign('purchase_payments_purchase_id_foreign');
            $table->dropForeign('purchase_payments_supplier_id_foreign');
            $table->dropForeign('purchase_payments_supplier_payment_id_foreign');
            $table->dropForeign('purchase_payments_supplier_return_id_foreign');
        });
    }
};
