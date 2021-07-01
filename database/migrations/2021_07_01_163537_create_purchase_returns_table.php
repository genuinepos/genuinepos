<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_returns', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id');
			$table->bigInteger('purchase_id')->unsigned()->nullable()->index('purchase_returns_purchase_id_foreign');
			$table->bigInteger('admin_id')->unsigned()->nullable();
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->index('purchase_returns_warehouse_id_foreign');
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('purchase_returns_branch_id_foreign');
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('purchase_returns_supplier_id_foreign');
			$table->boolean('return_type')->nullable()->comment('1=purchase_invoice_return;2=supplier_purchase_return');
			$table->decimal('total_return_amount', 22)->default(0.00);
			$table->decimal('total_return_due', 22)->default(0.00);
			$table->decimal('total_return_due_received', 22)->default(0.00);
			$table->decimal('purchase_tax_percent', 22)->default(0.00);
			$table->decimal('purchase_tax_amount', 22)->default(0.00);
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
		Schema::drop('purchase_returns');
	}

}
