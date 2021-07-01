<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchases', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->index('purchases_warehouse_id_foreign');
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('purchases_branch_id_foreign');
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('purchases_supplier_id_foreign');
			$table->boolean('pay_term')->nullable();
			$table->bigInteger('pay_term_number')->nullable();
			$table->bigInteger('total_item');
			$table->decimal('net_total_amount', 22)->default(0.00);
			$table->decimal('order_discount', 22)->default(0.00);
			$table->boolean('order_discount_type')->default(1);
			$table->decimal('order_discount_amount', 22)->default(0.00);
			$table->string('shipment_details')->nullable();
			$table->decimal('shipment_charge', 22)->default(0.00);
			$table->text('purchase_note')->nullable();
			$table->bigInteger('purchase_tax_id')->unsigned()->nullable();
			$table->decimal('purchase_tax_percent', 22)->default(0.00);
			$table->decimal('purchase_tax_amount', 22)->default(0.00);
			$table->decimal('total_purchase_amount', 22)->default(0.00);
			$table->decimal('paid', 22)->default(0.00);
			$table->decimal('due', 22)->default(0.00);
			$table->decimal('purchase_return_amount', 22)->default(0.00);
			$table->decimal('purchase_return_due', 22)->default(0.00);
			$table->text('payment_note')->nullable();
			$table->bigInteger('admin_id')->unsigned()->nullable();
			$table->boolean('purchase_status')->default(1);
			$table->string('date')->nullable();
			$table->string('time', 191)->nullable();
			$table->dateTime('report_date')->nullable();
			$table->string('month')->nullable();
			$table->string('year')->nullable();
			$table->boolean('is_last_created')->default(0);
			$table->boolean('is_return_available')->default(0);
			$table->string('attachment')->nullable();
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
		Schema::drop('purchases');
	}

}
