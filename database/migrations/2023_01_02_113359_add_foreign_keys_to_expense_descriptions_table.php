<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExpenseDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_descriptions', function (Blueprint $table) {
            $table->foreign(['expense_category_id'])->references(['id'])->on('expanse_categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['expense_id'])->references(['id'])->on('expanses')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_descriptions', function (Blueprint $table) {
            $table->dropForeign('expense_descriptions_expense_category_id_foreign');
            $table->dropForeign('expense_descriptions_expense_id_foreign');
        });
    }
}
