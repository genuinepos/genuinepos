<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHrmPayrollPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrm_payroll_payments', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['admin_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payroll_id'])->references(['id'])->on('hrm_payrolls')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_payroll_payments', function (Blueprint $table) {
            $table->dropForeign('hrm_payroll_payments_account_id_foreign');
            $table->dropForeign('hrm_payroll_payments_admin_id_foreign');
            $table->dropForeign('hrm_payroll_payments_payment_method_id_foreign');
            $table->dropForeign('hrm_payroll_payments_payroll_id_foreign');
        });
    }
}
