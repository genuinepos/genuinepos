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
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('transaction_type')->default(0);
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('plan_id');
            $table->boolean('is_trial_plan')->default(0);
            $table->bigInteger('increase_shop_count')->default(0);
            $table->string('payment_method_provider_name')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->string('payment_trans_id')->nullable();
            $table->decimal('subtotal', 22, 2)->default(0);
            $table->decimal('discount', 22, 2)->default(0);
            $table->decimal('total_payable_amount', 22, 2);
            $table->decimal('paid', 22, 2)->default(0);
            $table->decimal('due', 22, 2)->default(0);
            $table->boolean('payment_status')->default(0);
            $table->timestamp('payment_date')->nullable();
            $table->text('detail')->nullable()->comment('store payment method api response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
    }
};
