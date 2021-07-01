<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->boolean('type')->nullable()->comment('1=customer,2=supplier,3=both');
			$table->string('contact_id')->nullable();
			$table->bigInteger('customer_group_id')->unsigned()->nullable()->index('customers_customer_group_id_foreign');
			$table->string('name');
			$table->string('business_name')->nullable();
			$table->string('phone')->nullable();
			$table->string('alternative_phone')->nullable();
			$table->string('landline')->nullable();
			$table->string('email')->nullable();
			$table->string('date_of_birth')->nullable();
			$table->string('tax_number')->nullable();
			$table->decimal('opening_balance', 22)->default(0.00);
			$table->boolean('pay_term')->nullable()->comment('1=months,2=days');
			$table->integer('pay_term_number')->nullable();
			$table->text('address')->nullable();
			$table->text('shipping_address')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('country')->nullable();
			$table->string('zip_code')->nullable();
			$table->decimal('total_sale', 22)->default(0.00);
			$table->decimal('total_paid', 22)->default(0.00);
			$table->decimal('total_sale_due', 22)->default(0.00);
			$table->decimal('total_sale_return_due', 22)->default(0.00);
			$table->boolean('status')->default(1);
			$table->boolean('is_walk_in_customer')->default(0);
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
		Schema::drop('customers');
	}

}
