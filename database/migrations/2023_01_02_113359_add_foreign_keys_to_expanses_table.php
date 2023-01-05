<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExpansesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expanses', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['expense_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['transfer_branch_to_branch_id'])->references(['id'])->on('transfer_stock_branch_to_branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expanses', function (Blueprint $table) {
            $table->dropForeign('expanses_branch_id_foreign');
            $table->dropForeign('expanses_expense_account_id_foreign');
            $table->dropForeign('expanses_transfer_branch_to_branch_id_foreign');
        });
    }
}
