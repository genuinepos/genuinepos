<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSchemasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_schemas', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name')->nullable();
			$table->string('format')->nullable();
			$table->string('start_from', 191)->nullable();
			$table->boolean('number_of_digit')->nullable();
			$table->boolean('is_default')->default(0);
			$table->string('prefix', 191)->nullable();
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
		Schema::drop('invoice_schemas');
	}

}
