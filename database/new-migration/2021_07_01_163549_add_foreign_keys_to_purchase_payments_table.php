<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchasePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_payments', function(Blueprint $table)
		{
			$table->foreign('account_id')->references('id')->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_payments', function(Blueprint $table)
		{
			$table->dropForeign('purchase_payments_account_id_foreign');
			$table->dropForeign('purchase_payments_purchase_id_foreign');
			$table->dropForeign('purchase_payments_supplier_id_foreign');
		});
	}

}
