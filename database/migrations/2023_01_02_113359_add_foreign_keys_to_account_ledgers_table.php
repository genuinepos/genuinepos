<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['adjustment_id'])->references(['id'])->on('stock_adjustments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['contra_credit_id'])->references(['id'])->on('contras')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['contra_debit_id'])->references(['id'])->on('contras')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['customer_payment_id'])->references(['id'])->on('customer_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['expense_id'])->references(['id'])->on('expanses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['expense_payment_id'])->references(['id'])->on('expanse_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['loan_id'])->references(['id'])->on('loans')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['loan_payment_id'])->references(['id'])->on('loan_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payroll_id'])->references(['id'])->on('hrm_payrolls')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payroll_payment_id'])->references(['id'])->on('hrm_payroll_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['production_id'])->references(['id'])->on('productions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['purchase_payment_id'])->references(['id'])->on('purchase_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['purchase_return_id'])->references(['id'])->on('purchase_returns')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_payment_id'])->references(['id'])->on('sale_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_return_id'])->references(['id'])->on('sale_returns')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['stock_adjustment_recover_id'])->references(['id'])->on('stock_adjustment_recovers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['supplier_payment_id'])->references(['id'])->on('supplier_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
            $table->dropForeign('account_ledgers_account_id_foreign');
            $table->dropForeign('account_ledgers_adjustment_id_foreign');
            $table->dropForeign('account_ledgers_branch_id_foreign');
            $table->dropForeign('account_ledgers_contra_credit_id_foreign');
            $table->dropForeign('account_ledgers_contra_debit_id_foreign');
            $table->dropForeign('account_ledgers_customer_payment_id_foreign');
            $table->dropForeign('account_ledgers_expense_id_foreign');
            $table->dropForeign('account_ledgers_expense_payment_id_foreign');
            $table->dropForeign('account_ledgers_loan_id_foreign');
            $table->dropForeign('account_ledgers_loan_payment_id_foreign');
            $table->dropForeign('account_ledgers_payroll_id_foreign');
            $table->dropForeign('account_ledgers_payroll_payment_id_foreign');
            $table->dropForeign('account_ledgers_production_id_foreign');
            $table->dropForeign('account_ledgers_purchase_id_foreign');
            $table->dropForeign('account_ledgers_purchase_payment_id_foreign');
            $table->dropForeign('account_ledgers_purchase_return_id_foreign');
            $table->dropForeign('account_ledgers_sale_id_foreign');
            $table->dropForeign('account_ledgers_sale_payment_id_foreign');
            $table->dropForeign('account_ledgers_sale_return_id_foreign');
            $table->dropForeign('account_ledgers_stock_adjustment_recover_id_foreign');
            $table->dropForeign('account_ledgers_supplier_payment_id_foreign');
        });
    }
}
