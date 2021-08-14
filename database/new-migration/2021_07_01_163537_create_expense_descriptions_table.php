<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseDescriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense_descriptions', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('expense_id')->unsigned()->nullable()->index('expense_descriptions_expense_id_foreign');
			$table->bigInteger('expense_category_id')->unsigned()->nullable()->index('expense_descriptions_expense_category_id_foreign');
			$table->decimal('amount', 22)->default(0.00);
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
		Schema::drop('expense_descriptions');
	}

}
