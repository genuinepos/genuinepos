<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayrollAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hrm_payroll_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('payroll_id')->unsigned()->nullable()->index('hrm_payroll_allowances_payroll_id_foreign');
			$table->string('allowance_name')->nullable();
			$table->string('amount_type')->default('1');
			$table->decimal('allowance_percent', 22)->default(0.00);
			$table->decimal('allowance_amount', 22)->default(0.00);
			$table->string('date')->nullable();
			$table->string('month')->nullable();
			$table->string('year')->nullable();
			$table->dateTime('report_date_ts')->nullable();
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
		Schema::drop('hrm_payroll_allowances');
	}

}
