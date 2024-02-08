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
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->decimal('total_amount', 22, 2)->default(0);
            $table->decimal('due_amount', 22, 2)->default(0);
            $table->integer('initial_shop_count')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('expire_at')->nullable()->comment('for installation due payment');
            $table->timestamp('canceled_at')->nullable();
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
