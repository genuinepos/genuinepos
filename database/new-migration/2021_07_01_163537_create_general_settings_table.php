<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_settings', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('business')->nullable();
			$table->text('tax')->nullable();
			$table->text('product')->nullable();
			$table->text('sale')->nullable();
			$table->text('pos')->nullable();
			$table->text('purchase')->nullable();
			$table->text('dashboard')->nullable();
			$table->text('system')->nullable();
			$table->text('prefix')->nullable();
			$table->longText('send_es_settings')->nullable()->comment('send email and sms settings');
			$table->text('email_setting')->nullable();
			$table->text('sms_setting')->nullable();
			$table->text('modules')->nullable();
			$table->text('reward_poing_settings')->nullable();
			$table->boolean('multi_branches')->default(0)->comment('is_activated');
			$table->boolean('hrm')->default(0)->comment('is_activated');
			$table->boolean('services')->default(0)->comment('is_activated');
			$table->boolean('menufacturing')->default(0)->comment('is_activated');
			$table->boolean('projects')->default(0)->comment('is_activated');
			$table->boolean('essentials')->default(0)->comment('is_activated');
			$table->boolean('e_commerce')->default(0)->comment('is_activated');
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
		Schema::drop('general_settings');
	}

}
