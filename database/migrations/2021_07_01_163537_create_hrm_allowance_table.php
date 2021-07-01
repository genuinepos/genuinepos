<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmAllowanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hrm_allowance', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('description');
			$table->string('type');
			$table->bigInteger('employee_id')->unsigned()->nullable()->index('hrm_allowance_employee_id_foreign');
			$table->boolean('amount_type')->default(1)->comment('1=fixed;2=percentage');
			$table->decimal('amount', 22)->default(0.00);
			$table->string('applicable_date')->nullable();
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
		Schema::drop('hrm_allowance');
	}

}
