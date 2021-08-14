<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hrm_attendances', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('at_date');
			$table->bigInteger('user_id')->unsigned()->index('hrm_attendances_user_id_foreign');
			$table->string('clock_in')->nullable();
			$table->string('clock_out')->nullable();
			$table->string('work_duration')->nullable();
			$table->text('clock_in_note')->nullable();
			$table->text('clock_out_note')->nullable();
			$table->string('month')->nullable();
			$table->string('year')->nullable();
			$table->dateTime('clock_in_ts')->nullable();
			$table->dateTime('clock_out_ts')->nullable();
			$table->dateTime('at_date_ts')->nullable();
			$table->boolean('is_completed')->default(0);
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
		Schema::drop('hrm_attendances');
	}

}
