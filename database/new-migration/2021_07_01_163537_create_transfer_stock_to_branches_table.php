<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transfer_stock_to_branches', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id');
			$table->boolean('status')->default(1)->comment('1=pending;2=partial;3=completed');
			$table->bigInteger('warehouse_id')->unsigned()->index('transfer_stock_to_branches_warehouse_id_foreign')->comment('form_warehouse');
			$table->bigInteger('branch_id')->unsigned()->index('transfer_stock_to_branches_branch_id_foreign')->comment('to_branch');
			$table->decimal('total_item', 22);
			$table->decimal('total_send_qty', 22)->default(0.00);
			$table->decimal('total_received_qty', 22)->default(0.00);
			$table->decimal('net_total_amount', 22);
			$table->decimal('shipping_charge', 22)->default(0.00);
			$table->text('additional_note')->nullable();
			$table->text('receiver_note')->nullable();
			$table->string('date')->nullable();
			$table->string('month')->nullable();
			$table->string('year')->nullable();
			$table->dateTime('report_date')->nullable();
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
		Schema::drop('transfer_stock_to_branches');
	}

}
