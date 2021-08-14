<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvJobSheetsPartsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sv_job_sheets_parts', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('job_sheet_id')->unsigned()->index('sv_job_sheets_parts_job_sheet_id_foreign');
			$table->bigInteger('product_id')->unsigned()->nullable()->index('sv_job_sheets_parts_product_id_foreign');
			$table->bigInteger('product_variant_id')->unsigned()->nullable()->index('sv_job_sheets_parts_product_variant_id_foreign');
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
		Schema::drop('sv_job_sheets_parts');
	}

}
