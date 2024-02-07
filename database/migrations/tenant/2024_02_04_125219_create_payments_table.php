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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('transaction_id')->nullable();
            $table->float('subtotal');
            $table->float('discount')->default(0);
            $table->float('total');
            $table->tinyInteger('status');
            $table->tinyInteger('payment_type')->nullable();
            $table->timestamp('payment_at');
            $table->text('detail')->nullable()->comment('store payment method api response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
