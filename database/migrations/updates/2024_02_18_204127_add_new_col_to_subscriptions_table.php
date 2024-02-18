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
            if (!Schema::hasColumn('subscriptions', 'initial_business_period')) {

                $table->string('initial_business_price_period')->after('initial_plan_price')->nullable();
            }

            if (!Schema::hasColumn('subscriptions', 'initial_business_period_count')) {

                $table->bigInteger('initial_business_period_count')->after('initial_business_price')->nullable();
            }

            if (!Schema::hasColumn('subscriptions', 'initial_business_subtotal')) {

                $table->decimal('initial_business_subtotal', 22, 2)->after('current_shop_count')->default(0);
            }

            if (!Schema::hasColumn('subscriptions', 'initial_business_start_date')) {

                $table->timestamp('initial_business_start_date')->after('initial_plan_start_date')->nullable();
            }

            if (!Schema::hasColumn('subscriptions', 'initial_period_count')) {

                $table->bigInteger('initial_period_count')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
