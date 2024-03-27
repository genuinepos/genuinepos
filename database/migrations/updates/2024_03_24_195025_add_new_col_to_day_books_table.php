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
        Schema::table('day_books', function (Blueprint $table) {

            if (!Schema::hasColumn('day_books', 'stock_issue_id')) {

                $table->unsignedBigInteger('stock_issue_id')->after('transfer_stock_id')->nullable();
                $table->foreign('stock_issue_id')->references('id')->on('stock_issues')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('day_books', function (Blueprint $table) {
            $table->dropForeign(['stock_issue_id']);
            $table->dropColumn('stock_issue_id');
        });
    }
};
