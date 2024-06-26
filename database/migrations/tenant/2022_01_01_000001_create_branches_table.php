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
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_branch_id')->nullable();
            $table->tinyInteger('branch_type')->default(1);
            $table->string('name')->nullable();
            $table->string('area_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('alternate_phone_number')->nullable();
            $table->string('bin')->nullable();
            $table->string('tin')->nullable();
            $table->string('country', 191)->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo', 191)->nullable();
            $table->boolean('purchase_permission')->default(1);
            $table->date('expire_date')->nullable();
            $table->unsignedBigInteger('shop_expire_date_history_id')->nullable();
            $table->string('current_price_period', 20)->nullable();
            $table->timestamps();

            $table->foreign('parent_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('shop_expire_date_history_id')->references('id')->on('shop_expire_date_histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
};
