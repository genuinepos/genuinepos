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

            if (Schema::hasColumn('subscriptions', 'initial_price_period')) {
                $table->dropColumn('initial_price_period');
            }

            if (Schema::hasColumn('subscriptions', 'initial_period_count')) {
                $table->dropColumn('initial_period_count');
            }

            if (Schema::hasColumn('subscriptions', 'initial_plan_price')) {
                $table->dropColumn('initial_plan_price');
            }

            if (Schema::hasColumn('subscriptions', 'initial_business_start_date')) {
                $table->dropColumn('initial_business_start_date');
            }

            if (Schema::hasColumn('subscriptions', 'is_completed_startup')) {
                $table->dropColumn('is_completed_startup');
            }

            if (Schema::hasColumn('subscriptions', 'initial_business_price_period')) {

                $table->dropColumn('initial_business_price_period');
            }

            if (Schema::hasColumn('subscriptions', 'initial_business_period_count')) {
                $table->dropColumn('initial_business_period_count');
            }

            if (Schema::hasColumn('subscriptions', 'initial_business_price')) {
                $table->dropColumn('initial_business_price');
            }

            if (Schema::hasColumn('subscriptions', 'initial_shop_count')) {
                $table->dropColumn('initial_shop_count');
            }

            if (Schema::hasColumn('subscriptions', 'initial_business_subtotal')) {
                $table->dropColumn('initial_business_subtotal');
            }

            if (Schema::hasColumn('subscriptions', 'initial_subtotal')) {
                $table->dropColumn('initial_subtotal');
            }

            if (Schema::hasColumn('subscriptions', 'initial_discount')) {
                $table->dropColumn('initial_discount');
            }

            if (Schema::hasColumn('subscriptions', 'initial_total_payable_amount')) {
                $table->dropColumn('initial_total_payable_amount');
            }

            if (Schema::hasColumn('subscriptions', 'initial_due_amount')) {
                $table->dropColumn('initial_due_amount');
            }

            if (Schema::hasColumn('subscriptions', 'status')) {
                $table->boolean('status')->change()->default(1);
            }

            if (Schema::hasColumn('subscriptions', 'initial_payment_status')) {
                $table->boolean('initial_payment_status')->change()->default(0);
            }

            if (Schema::hasColumn('subscriptions', 'initial_payment_status')) {
                $table->renameColumn('initial_payment_status', 'has_due_amount');
            }

            if (Schema::hasColumn('subscriptions', 'initial_plan_expire_date')) {
                $table->renameColumn('initial_plan_expire_date', 'due_repayment_date');
            }

            if (!Schema::hasColumn('subscriptions', 'business_start_date')) {
                $table->timestamp('business_start_date')->after('has_business')->nullable();
            }

            if (!Schema::hasColumn('subscriptions', 'business_adjustable_price')) {
                $table->string('business_adjustable_price', 20)->after('business_start_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('initial_price_period')->after('trial_start_date')->nullable();
            $table->bigInteger('initial_period_count')->after('initial_price_period')->nullable();
            $table->decimal('initial_plan_price', 22, 2)->after('initial_period_count')->nullable();
            $table->string('initial_business_price_period')->after('initial_plan_price')->nullable();
            $table->bigInteger('initial_business_period_count')->after('initial_business_price_period')->nullable();
            $table->decimal('initial_business_price', 22, 2)->after('initial_business_period_count')->default(0);
            $table->bigInteger('initial_shop_count')->after('initial_business_price')->nullable();

            $table->decimal('initial_business_subtotal', 22, 2)->after('current_shop_count')->default(0);
            $table->decimal('initial_subtotal', 22, 2)->after('initial_business_subtotal')->default(0);
            $table->decimal('initial_discount', 22, 2)->after('initial_subtotal')->default(0);
            $table->decimal('initial_total_payable_amount', 22, 2)->after('initial_discount')->default(0);
            $table->decimal('initial_due_amount', 22, 2)->after('initial_total_payable_amount')->default(0);

            $table->renameColumn('due_payment_status', 'initial_payment_status');
            $table->renameColumn('due_repayment_date', 'initial_plan_expire_date');
            $table->renameColumn('first_plan_start_date', 'initial_plan_start_date');
        });
    }
};
