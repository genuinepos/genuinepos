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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->tinyInteger('type');
            $table->string('contact_id', 255);
            $table->unsignedBigInteger('customer_group_id')->nullable();
            $table->string('name', 255);
            $table->string('phone', 255);
            $table->string('business_name', 255)->nullable();
            $table->string('alternative_phone', 255)->nullable();
            $table->string('landline', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('date_of_birth', 255)->nullable();
            $table->string('tax_number', 255)->nullable();
            $table->decimal('opening_balance', 22, 2)->default(0);
            $table->string('opening_balance_type')->nullable();
            $table->string('credit_limit', 255)->nullable();
            $table->tinyInteger('pay_term_number')->nullable();
            $table->tinyInteger('pay_term')->nullable();
            $table->text('address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_fixed')->default(0);
            $table->boolean('is_chain_shop_contact')->default(0);
            $table->decimal('reward_point')->default(0);
            $table->timestamps();

            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
