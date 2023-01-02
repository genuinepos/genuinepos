<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_branch_id_foreign');
            $table->dropForeign('sales_customer_id_foreign');
            $table->dropForeign('sales_sale_account_id_foreign');
        });
    }
}
