<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegistersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cash_registers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('cash_registers_branch_id_foreign');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->index('cash_registers_warehouse_id_foreign');
			$table->bigInteger('account_id')->unsigned()->nullable()->index('cash_registers_account_id_foreign');
			$table->bigInteger('admin_id')->unsigned()->nullable()->index('cash_registers_admin_id_foreign');
			$table->dateTime('closed_at')->nullable();
			$table->decimal('closed_amount', 22)->default(0.00);
			$table->bigInteger('total_card_slips')->nullable();
			$table->bigInteger('total_cheques')->nullable();
			$table->boolean('status')->default(1)->comment('1=open;0=closed;');
			$table->text('closing_note')->nullable();
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
		Schema::drop('cash_registers');
	}

}
