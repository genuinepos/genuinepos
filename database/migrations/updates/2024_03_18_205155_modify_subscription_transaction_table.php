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

            if (Schema::hasColumn('subscription_transactions', 'is_trial_plan')) {

                $table->dropColumn('is_trial_plan');
            }

            if (Schema::hasColumn('subscription_transactions', 'increase_shop_count')) {

                $table->dropColumn('increase_shop_count');
            }

            if (Schema::hasColumn('subscription_transactions', 'subtotal')) {

                $table->renameColumn('subtotal', 'net_total');
            }

            if (!Schema::hasColumn('subscription_transactions', 'details_type')) {

                $table->string('details_type', 50)->after('payment_date')->nullable();
            }

            if (Schema::hasColumn('subscription_transactions', 'detail')) {

                $table->renameColumn('detail', 'details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_transactions', function (Blueprint $table) {
            $table->boolean('is_trial_plan')->after('plan_id')->default(0);
            $table->bigInteger('increase_shop_count')->after('is_trial_plan')->default(0);
        });
    }
};
