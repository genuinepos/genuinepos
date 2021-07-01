<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchPaymentMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('branch_payment_methods', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('branch_id')->unsigned()->index('branch_payment_methods_branch_id_foreign');
			$table->string('method_name');
			$table->bigInteger('account_id')->unsigned()->nullable()->index('branch_payment_methods_account_id_foreign');
			$table->boolean('is_delete_in_update')->default(0);
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
		Schema::drop('branch_payment_methods');
	}

}
