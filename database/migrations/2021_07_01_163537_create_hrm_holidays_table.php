<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmHolidaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hrm_holidays', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('holiday_name');
			$table->string('start_date');
			$table->string('end_date');
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('hrm_holidays_branch_id_foreign');
			$table->boolean('is_all')->default(0);
			$table->text('notes')->nullable();
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
		Schema::drop('hrm_holidays');
	}

}
