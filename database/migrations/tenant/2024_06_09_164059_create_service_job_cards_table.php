<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_job_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->string('job_no', 255)->nullable();
            $table->unsignedBigInteger('customer_account_id');
            $table->tinyInteger('service_type');
            $table->text('address')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('device_model_id')->nullable();
            $table->string('serial_no', 255)->nullable();
            $table->string('password', 100)->nullable();
            $table->text('service_checklist')->nullable();
            $table->text('product_configuration')->nullable();
            $table->text('problems_report')->nullable();
            $table->text('product_condition')->nullable();
            $table->string('technician_comment', 255)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('document', 255)->nullable();
            $table->string('send_notification', 20)->nullable();
            $table->string('custom_field_1', 255)->nullable();
            $table->string('custom_field_2', 255)->nullable();
            $table->string('custom_field_3', 255)->nullable();
            $table->string('custom_field_4', 255)->nullable();
            $table->string('custom_field_5', 255)->nullable();
            $table->decimal('total_item', 22, 2)->default(0);
            $table->decimal('total_qty', 22, 2)->default(0);
            $table->decimal('total_cost', 22, 2)->default(0);
            $table->timestamp('date_ts');
            $table->timestamp('delivery_date_ts')->nullable();
            $table->timestamp('due_date_ts')->nullable();
            $table->timestamp('completed_at_ts')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('set null');
            $table->foreign('customer_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('device_id')->references('id')->on('service_devices')->onDelete('set null');
            $table->foreign('device_model_id')->references('id')->on('service_device_models')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('service_status')->onDelete('set null');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};
