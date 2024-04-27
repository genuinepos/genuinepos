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
        Schema::table('subscription_transactions', function (Blueprint $table) {

            if (!Schema::hasColumn('subscription_transactions', 'coupon_code')) {

                $table->string('coupon_code', 100)->after('net_total')->nullable();
            }

            if (!Schema::hasColumn('subscription_transactions', 'discount_percent')) {

                $table->decimal('discount_percent', 22, 2)->after('coupon_code')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_transactions', function (Blueprint $table) {
            $table->dropColumn('coupon_code');
            $table->dropColumn('discount_percent');
        });
    }
};
