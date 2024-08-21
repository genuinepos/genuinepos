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
        Schema::table('plans', function (Blueprint $table) {

            if (Schema::hasColumn('plans', 'currency_id')) {

                Schema::disableForeignKeyConstraints();
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
                Schema::enableForeignKeyConstraints();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->after('applicable_lifetime_years')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
        });
    }
};
