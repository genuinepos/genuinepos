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
        Schema::table('subscriptions', function (Blueprint $table) {

            if (!Schema::hasColumn('subscriptions', 'is_completed_branch_startup')) {

                $table->boolean('is_completed_branch_startup')->after('is_completed_startup')->default(0);
            }

            if (!Schema::hasColumn('subscriptions', 'initial_business_price')) {

                $table->boolean('initial_business_price')->after('initial_plan_price')->default(0);
            }

            if (!Schema::hasColumn('subscriptions', 'has_business')) {

                $table->boolean('has_business')->after('initial_plan_expire_date')->default(0);
            }

            if (!Schema::hasColumn('subscriptions', 'business_expire_date')) {

                $table->date('business_expire_date')->after('has_business')->nullable();
            }

            if (Schema::hasColumn('subscriptions', 'is_completed_startup')) {

                $table->renameColumn('is_completed_startup', 'is_completed_business_startup');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {

            $table->rename('is_completed_business_startup', 'is_completed_startup');
            $table->dropColumn('is_completed_branch_startup');
            $table->dropColumn('initial_business_price');
            $table->dropColumn('has_business');
            $table->dropColumn('business_expire_date');
        });
    }
};
