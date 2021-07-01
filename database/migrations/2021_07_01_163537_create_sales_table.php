<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id', 100)->nullable();
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('sales_branch_id_foreign');
			$table->bigInteger('warehouse_id')->unsigned()->nullable()->index('sales_warehouse_id_foreign');
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('sales_customer_id_foreign');
			$table->boolean('pay_term')->nullable();
			$table->bigInteger('pay_term_number')->nullable();
			$table->bigInteger('total_item');
			$table->decimal('net_total_amount', 22)->default(0.00);
			$table->boolean('order_discount_type')->default(1);
			$table->decimal('order_discount', 22)->default(0.00);
			$table->decimal('order_discount_amount', 22)->default(0.00);
			$table->string('shipment_details')->nullable();
			$table->text('shipment_address')->nullable();
			$table->decimal('shipment_charge', 22)->default(0.00);
			$table->boolean('shipment_status')->nullable();
			$table->text('delivered_to')->nullable();
			$table->text('sale_note')->nullable();
			$table->decimal('order_tax_percent', 22)->default(0.00);
			$table->decimal('order_tax_amount', 22)->default(0.00);
			$table->decimal('total_payable_amount', 22)->default(0.00);
			$table->decimal('paid', 22)->default(0.00);
			$table->decimal('change_amount', 22)->default(0.00);
			$table->decimal('due', 22)->default(0.00);
			$table->boolean('is_return_available')->default(0);
			$table->boolean('ex_status')->default(0)->comment('0=exchangeed,1=exchanged');
			$table->decimal('sale_return_amount', 22)->default(0.00);
			$table->decimal('sale_return_due', 22)->default(0.00);
			$table->text('payment_note')->nullable();
			$table->bigInteger('admin_id')->unsigned()->nullable();
			$table->boolean('status')->default(1)->comment('1=final;2=draft;3=challan;4=quatation;5=hold;6=suspended');
			$table->boolean('is_fixed_challen')->default(0);
			$table->string('date', 191)->nullable();
			$table->string('time', 191)->nullable();
			$table->dateTime('report_date')->nullable();
			$table->string('month', 191)->nullable();
			$table->string('year', 191)->nullable();
			$table->string('attachment', 191)->nullable();
			$table->boolean('created_by')->default(1)->comment('1=add_sale;2=pos');
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
		Schema::drop('sales');
	}

}
