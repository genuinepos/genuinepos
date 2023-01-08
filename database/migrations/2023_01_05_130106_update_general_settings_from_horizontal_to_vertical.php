<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('general_settings');
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id('id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->foreignId('branch_id')->nullable()->references('id')->on('branches')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_settings');
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('business')->nullable();
            $table->longText('tax')->nullable();
            $table->longText('product')->nullable();
            $table->longText('sale')->nullable();
            $table->longText('pos')->nullable();
            $table->longText('purchase')->nullable();
            $table->longText('dashboard')->nullable();
            $table->longText('system')->nullable();
            $table->longText('prefix')->nullable();
            $table->string('send_es_settings')->nullable();
            $table->longText('email_setting')->nullable();
            $table->longText('sms_setting')->nullable();
            $table->longText('modules')->nullable();
            $table->longText('reward_point_settings')->nullable();
            $table->text('mf_settings')->nullable()->comment('manufacturing_settings');
            $table->boolean('multi_branches')->default(false)->comment('is_activated');
            $table->boolean('hrm')->default(false)->comment('is_activated');
            $table->boolean('services')->default(false)->comment('is_activated');
            $table->boolean('manufacturing')->default(false)->comment('is_activated');
            $table->boolean('projects')->default(false)->comment('is_activated');
            $table->boolean('essentials')->default(false)->comment('is_activated');
            $table->boolean('e_commerce')->default(false)->comment('is_activated');
            $table->timestamps();
        });
    }
};
