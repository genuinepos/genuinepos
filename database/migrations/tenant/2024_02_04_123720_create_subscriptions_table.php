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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name', 255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable()->comment('initial plan and upgraded plan id will be go here.');
            $table->timestamp('trial_start_date')->nullable();
            // $table->string('initial_price_period')->nullable();
            // $table->bigInteger('initial_period_count')->nullable();
            // $table->decimal('initial_plan_price', 22, 2)->nullable();
            // $table->string('initial_business_price_period')->nullable();
            // $table->bigInteger('initial_business_period_count')->nullable();
            // $table->decimal('initial_business_price', 22, 2)->default(0);
            // $table->bigInteger('initial_shop_count')->nullable();
            $table->bigInteger('current_shop_count')->nullable();
            // $table->decimal('initial_business_subtotal', 22, 2)->default(0);
            // $table->decimal('initial_subtotal', 22, 2)->default(0);
            // $table->decimal('initial_discount', 22, 2)->default(0);
            // $table->decimal('initial_total_payable_amount', 22, 2)->default(0);
            // $table->decimal('initial_due_amount', 22, 2)->default(0);
            $table->boolean('status')->default(1);
            $table->timestamp('initial_plan_start_date')->nullable();
            $table->boolean('has_due_amount')->default(0);
            $table->date('due_repayment_date')->nullable()->comment('if has any due so a date will come, on the other hand this col will be null');
            $table->boolean('has_business')->default(0);
            $table->timestamp('business_start_date')->nullable();
            $table->string('business_price_period')->nullable();
            $table->decimal('business_adjustable_price', 22, 2)->nullable();
            $table->date('business_expire_date')->nullable();
            $table->boolean('is_completed_business_startup')->default(0);
            $table->boolean('is_completed_branch_startup')->default(0);
            $table->timestamp('canceled_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
