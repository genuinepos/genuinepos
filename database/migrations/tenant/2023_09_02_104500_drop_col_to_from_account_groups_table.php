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
        Schema::table('account_groups', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }
};