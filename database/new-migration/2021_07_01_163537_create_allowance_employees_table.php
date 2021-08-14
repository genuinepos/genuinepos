<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowanceEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('allowance_employees', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('allowance_id')->unsigned()->nullable()->index('allowance_employees_allowance_id_foreign');
			$table->bigInteger('user_id')->unsigned()->nullable()->index('allowance_employees_user_id_foreign');
			$table->boolean('is_delete_in_update')->nullable()->default(0);
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
		Schema::drop('allowance_employees');
	}

}
