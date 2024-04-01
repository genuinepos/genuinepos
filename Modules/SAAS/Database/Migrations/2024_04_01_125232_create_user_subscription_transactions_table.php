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
        Schema::create('user_subscription_transactions', function (Blueprint $table) {

            $table->id();
            $table->tinyInteger('transaction_type')->default(0);
            $table->unsignedBigInteger('user_subscription_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('payment_method_provider_name')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->string('payment_trans_id')->nullable();
            $table->decimal('net_total', 22, 2)->default(0);
            $table->decimal('discount', 22, 2)->default(0);
            $table->decimal('total_payable_amount', 22, 2);
            $table->decimal('paid', 22, 2)->default(0);
            $table->decimal('due', 22, 2)->default(0);
            $table->boolean('payment_status')->default(0);
            $table->timestamp('payment_date')->nullable();
            $table->string('details_type', 50)->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->foreign('user_subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscription_transactions');
    }
};
