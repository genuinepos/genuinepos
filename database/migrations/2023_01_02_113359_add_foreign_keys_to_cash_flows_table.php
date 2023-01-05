<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['customer_payment_id'])->references(['id'])->on('customer_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['expanse_payment_id'])->references(['id'])->on('expanse_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['loan_id'])->references(['id'])->on('loans')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['loan_payment_id'])->references(['id'])->on('loan_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['money_receipt_id'])->references(['id'])->on('money_receipts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payroll_id'])->references(['id'])->on('hrm_payrolls')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payroll_payment_id'])->references(['id'])->on('hrm_payroll_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['purchase_payment_id'])->references(['id'])->on('purchase_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['receiver_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_payment_id'])->references(['id'])->on('sale_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sender_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->dropForeign('cash_flows_account_id_foreign');
            $table->dropForeign('cash_flows_customer_payment_id_foreign');
            $table->dropForeign('cash_flows_expanse_payment_id_foreign');
            $table->dropForeign('cash_flows_loan_id_foreign');
            $table->dropForeign('cash_flows_loan_payment_id_foreign');
            $table->dropForeign('cash_flows_money_receipt_id_foreign');
            $table->dropForeign('cash_flows_payroll_id_foreign');
            $table->dropForeign('cash_flows_payroll_payment_id_foreign');
            $table->dropForeign('cash_flows_purchase_payment_id_foreign');
            $table->dropForeign('cash_flows_receiver_account_id_foreign');
            $table->dropForeign('cash_flows_sale_payment_id_foreign');
            $table->dropForeign('cash_flows_sender_account_id_foreign');
            $table->dropForeign('cash_flows_supplier_payment_id_foreign');
        });
    }
}
