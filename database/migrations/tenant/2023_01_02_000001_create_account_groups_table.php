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
        Schema::create('account_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sorting_number');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('parent_group_id')->nullable();
            $table->boolean('is_reserved')->default(0);
            $table->boolean('is_allowed_bank_details')->default(0);
            $table->boolean('is_bank_or_cash_ac')->default(0);
            $table->boolean('is_fixed_tax_calculator')->default(0);
            $table->boolean('is_default_tax_calculator')->default(0);
            $table->boolean('is_main_group')->default(0);
            $table->boolean('is_sub_group')->default(0);
            $table->boolean('is_parent_sub_group')->default(0);
            $table->boolean('is_sub_sub_group')->default(0);
            $table->boolean('is_parent_sub_sub_group')->default(0);
            $table->integer('main_group_number')->nullable();
            $table->integer('sub_group_number')->nullable();
            $table->integer('sub_sub_group_number')->nullable();
            $table->string('main_group_name')->nullable();
            $table->string('sub_group_name')->nullable();
            $table->string('sub_sub_group_name')->nullable();
            $table->string('default_balance_type', 10)->nullable();
            $table->timestamps();
            $table->boolean('is_global')->default(false);

            $table->foreign('parent_group_id')->references('id')->on('account_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_groups');
    }
};
