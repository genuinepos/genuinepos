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
        Schema::create('stock_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('voucher_no', 255)->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('reported_by_id')->nullable();
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22, 2)->default(0);
            $table->decimal('net_total_amount', 22, 2)->default(0);
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->string('remarks', 255)->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['department_id'])->references(['id'])->on('hrm_departments')->onDelete('CASCADE');
            $table->foreign(['reported_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_issues');
    }
};
