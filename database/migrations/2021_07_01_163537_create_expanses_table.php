<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expanses', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('invoice_id')->nullable();
			$table->bigInteger('branch_id')->unsigned()->nullable()->index('expanses_branch_id_foreign');
			$table->string('attachment')->nullable();
			$table->text('note')->nullable();
			$table->decimal('tax_percent', 22)->default(0.00);
			$table->decimal('tax_amount', 22)->default(0.00);
			$table->decimal('total_amount', 22)->default(0.00);
			$table->decimal('net_total_amount', 22)->default(0.00);
			$table->decimal('paid', 22)->default(0.00);
			$table->decimal('due', 22)->default(0.00);
			$table->string('date');
			$table->string('month');
			$table->string('year');
			$table->bigInteger('admin_id')->unsigned()->nullable();
			$table->timestamp('report_date')->default(DB::raw('CURRENT_TIMESTAMP'));
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
		Schema::drop('expanses');
	}

}
