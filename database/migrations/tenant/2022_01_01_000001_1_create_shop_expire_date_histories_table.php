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
        Schema::create('shop_expire_date_histories', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('shop_id')->comment('branch / shop id');
            // $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('plan_id');
            $table->string('price_period', 20)->nullable();
            $table->decimal('adjustable_price', 22, 2)->nullable();
            $table->tinyInteger('shop_count'); // = 8;
            $table->timestamp('start_date'); // = 04-02-24;
            $table->date('expire_date'); // = 04-02-25;
            $table->integer('created_count'); // = 8;
            $table->integer('left_count'); // = 0;

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_expire_date_histories');
    }
};
