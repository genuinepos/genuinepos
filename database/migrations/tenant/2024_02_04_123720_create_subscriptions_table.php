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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable()->comment('initial plan and upgraded plan id will be go here.');
            $table->timestamp('trial_start_date')->nullable();
            $table->string('initial_price_period')->nullable();
            $table->string('initial_period_count')->nullable();
            $table->decimal('initial_plan_price', 22, 2)->nullable();
            $table->bigInteger('initial_shop_count')->nullable();
            $table->bigInteger('current_shop_count')->nullable();
            $table->decimal('initial_subtotal', 22, 2)->default(0);
            $table->decimal('initial_discount', 22, 2)->default(0);
            $table->decimal('initial_total_payable_amount', 22, 2)->default(0);
            $table->decimal('initial_due_amount', 22, 2)->default(0);
            $table->tinyInteger('initial_payment_status')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('initial_plan_start_date')->nullable();
            $table->date('initial_plan_expire_date')->nullable()->comment('for installation due payment, if initial payable amount is paid then this col will be null');
            $table->boolean('is_completed_startup')->default(0);
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
