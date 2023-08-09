<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_job_sheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable()->index('sv_job_sheets_customer_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('sv_job_sheets_branch_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('sv_job_sheets_user_id_foreign');
            $table->unsignedBigInteger('brand_id')->nullable()->index('sv_job_sheets_brand_id_foreign');
            $table->unsignedBigInteger('device_id')->nullable()->index('sv_job_sheets_device_id_foreign');
            $table->unsignedBigInteger('model_id')->nullable()->index('sv_job_sheets_model_id_foreign');
            $table->unsignedBigInteger('status_id')->nullable()->index('sv_job_sheets_status_id_foreign');
            $table->boolean('is_completed')->default(false);
            $table->tinyInteger('service_type')->nullable();
            $table->mediumText('address')->nullable();
            $table->decimal('cost', 22)->default(0);
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
        Schema::dropIfExists('sv_job_sheets');
    }
};
