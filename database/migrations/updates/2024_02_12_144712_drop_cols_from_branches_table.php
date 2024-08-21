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
        Schema::table('branches', function (Blueprint $table) {

            if (Schema::hasColumn('branches', 'timezone')) {

                $table->dropColumn('timezone');
            }

            if (Schema::hasColumn('branches', 'time_format')) {

                $table->dropColumn('time_format');
            }

            if (Schema::hasColumn('branches', 'date_format')) {

                $table->dropColumn('date_format');
            }

            if (Schema::hasColumn('branches', 'stock_accounting_method')) {

                $table->dropColumn('stock_accounting_method');
            }

            if (Schema::hasColumn('branches', 'account_start_date')) {

                $table->dropColumn('account_start_date');
            }

            if (Schema::hasColumn('branches', 'financial_year_start_month')) {

                $table->dropColumn('financial_year_start_month');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->string('timezone')->after('purchase_permission')->nullable();
            $table->string('time_format')->after('timezone')->nullable();
            $table->string('date_format')->after('time_format')->nullable();
            $table->tinyInteger('stock_accounting_method')->after('date_format')->nullable();
            $table->date('account_start_date')->after('stock_accounting_method')->nullable();
            $table->integer('financial_year_start_month')->after('account_start_date')->nullable();
        });
    }
};
