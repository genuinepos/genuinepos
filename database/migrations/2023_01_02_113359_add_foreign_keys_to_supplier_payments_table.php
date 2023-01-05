<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSupplierPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->dropForeign('supplier_payments_account_id_foreign');
            $table->dropForeign('supplier_payments_admin_id_foreign');
            $table->dropForeign('supplier_payments_branch_id_foreign');
            $table->dropForeign('supplier_payments_payment_method_id_foreign');
            $table->dropForeign('supplier_payments_supplier_id_foreign');
        });
    }
}
