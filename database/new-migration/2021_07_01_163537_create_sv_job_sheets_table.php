<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvJobSheetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sv_job_sheets', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id')->nullable();
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('sv_job_sheets_customer_id_foreign');
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('sv_job_sheets_branch_id_foreign');
			$table->bigInteger('user_id')->unsigned()->nullable()->index('sv_job_sheets_user_id_foreign');
			$table->bigInteger('brand_id')->unsigned()->nullable()->index('sv_job_sheets_brand_id_foreign');
			$table->bigInteger('device_id')->unsigned()->nullable()->index('sv_job_sheets_device_id_foreign');
			$table->bigInteger('model_id')->unsigned()->nullable()->index('sv_job_sheets_model_id_foreign');
			$table->bigInteger('status_id')->unsigned()->nullable()->index('sv_job_sheets_status_id_foreign');
			$table->boolean('is_completed')->default(0);
			$table->boolean('service_type')->nullable();
			$table->text('address')->nullable();
			$table->decimal('cost', 22)->default(0.00);
			$table->text('checklist')->nullable();
			$table->string('serial_number')->nullable();
			$table->string('password')->nullable();
			$table->text('configuration')->nullable()->comment('Product Configuration');
			$table->text('Condition')->nullable()->comment('Condition Of The Product');
			$table->text('customer_report')->nullable()->comment('Problem Reported By The Customer');
			$table->text('technician_comment')->nullable();
			$table->string('delivery_date')->nullable();
			$table->string('send_notification')->nullable();
			$table->string('date')->nullable();
			$table->string('time')->nullable();
			$table->string('month')->nullable();
			$table->string('year')->nullable();
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
		Schema::drop('sv_job_sheets');
	}

}
