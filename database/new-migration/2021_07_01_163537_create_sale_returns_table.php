<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_returns', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id');
			$table->bigInteger('sale_id')->unsigned()->index('sale_returns_sale_id_foreign');
			$table->bigInteger('admin_id')->unsigned();
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->index('sale_returns_warehouse_id_foreign');
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('sale_returns_branch_id_foreign');
			$table->boolean('return_discount_type')->default(1);
			$table->decimal('return_discount', 22)->default(0.00);
			$table->decimal('return_discount_amount', 22)->default(0.00);
			$table->decimal('net_total_amount', 22)->default(0.00);
			$table->decimal('total_return_amount', 22)->default(0.00);
			$table->decimal('total_return_due', 22)->default(0.00);
			$table->decimal('total_return_due_pay', 22)->default(0.00);
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
		Schema::drop('sale_returns');
	}

}
