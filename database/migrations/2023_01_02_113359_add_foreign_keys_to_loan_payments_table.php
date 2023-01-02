<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_payments', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['company_id'])->references(['id'])->on('loan_companies')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_payments', function (Blueprint $table) {
            $table->dropForeign('loan_payments_account_id_foreign');
            $table->dropForeign('loan_payments_branch_id_foreign');
            $table->dropForeign('loan_payments_company_id_foreign');
            $table->dropForeign('loan_payments_payment_method_id_foreign');
            $table->dropForeign('loan_payments_user_id_foreign');
        });
    }
}
