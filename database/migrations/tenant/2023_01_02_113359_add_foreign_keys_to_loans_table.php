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
        Schema::table('loans', function (Blueprint $table) {
            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['created_user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['expense_id'])->references(['id'])->on('expanses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['loan_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['loan_company_id'])->references(['id'])->on('loan_companies')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('loans_account_id_foreign');
            $table->dropForeign('loans_branch_id_foreign');
            $table->dropForeign('loans_created_user_id_foreign');
            $table->dropForeign('loans_expense_id_foreign');
            $table->dropForeign('loans_loan_account_id_foreign');
            $table->dropForeign('loans_loan_company_id_foreign');
            $table->dropForeign('loans_purchase_id_foreign');
        });
    }
};
