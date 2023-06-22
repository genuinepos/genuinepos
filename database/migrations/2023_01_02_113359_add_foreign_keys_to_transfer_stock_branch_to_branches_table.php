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
        Schema::table('transfer_stock_branch_to_branches', function (Blueprint $table) {
            $table->foreign(['bank_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['expense_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['receiver_branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['receiver_warehouse_id'])->references(['id'])->on('warehouses')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['sender_branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sender_warehouse_id'])->references(['id'])->on('warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_stock_branch_to_branches', function (Blueprint $table) {
            $table->dropForeign('transfer_stock_branch_to_branches_bank_account_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branches_expense_account_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branches_payment_method_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branches_receiver_branch_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branches_receiver_warehouse_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branches_sender_branch_id_foreign');
            $table->dropForeign('transfer_stock_branch_to_branches_sender_warehouse_id_foreign');
        });
    }
};
