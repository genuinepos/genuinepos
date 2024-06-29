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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable()->comment('initial plan and upgraded plan id will be go here.');
            $table->timestamp('trial_start_date')->nullable();
            $table->bigInteger('current_shop_count')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('initial_plan_start_date')->nullable();
            $table->boolean('has_due_amount')->default(0);
            $table->date('due_repayment_date')->nullable()->comment('if has any due so a date will come, on the other hand this col will be null');
            $table->boolean('has_business')->default(0);
            $table->timestamp('business_start_date')->nullable();
            $table->string('business_price_period')->nullable();
            $table->date('business_expire_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
