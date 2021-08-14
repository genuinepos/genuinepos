<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierLedgersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_ledgers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('supplier_ledgers_supplier_id_foreign');
			$table->bigInteger('purchase_id')->unsigned()->nullable()->index('supplier_ledgers_purchase_id_foreign');
			$table->bigInteger('purchase_payment_id')->unsigned()->nullable()->index('supplier_ledgers_purchase_payment_id_foreign');
			$table->boolean('row_type')->default(1)->comment('1=purchase;2=purchase_payment3=opening_balance');
			$table->decimal('amount', 22)->nullable()->comment('only_for_opening_balance');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('supplier_ledgers');
	}

}
