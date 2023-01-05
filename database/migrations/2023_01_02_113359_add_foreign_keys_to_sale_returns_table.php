<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['customer_id'])->references(['id'])->on('customers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sale_return_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->dropForeign('sale_returns_branch_id_foreign');
            $table->dropForeign('sale_returns_customer_id_foreign');
            $table->dropForeign('sale_returns_sale_id_foreign');
            $table->dropForeign('sale_returns_sale_return_account_id_foreign');
            $table->dropForeign('sale_returns_warehouse_id_foreign');
        });
    }
}
