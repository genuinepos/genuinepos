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
        Schema::table('users', function (Blueprint $table) {

            $table->string('tenant_id', 255)->after('id')->nullable();
            $table->tinyInteger('user_type')->after('tenant_id')->default(1)->comment('1=authorized_user,2=subscriber_user');

            if (Schema::hasColumn('users', 'stripe_id')) {

                $table->dropColumn('stripe_id');
            }

            if (Schema::hasColumn('users', 'pm_type')) {

                $table->dropColumn('pm_type');
            }

            if (Schema::hasColumn('users', 'pm_last_four')) {

                $table->dropColumn('pm_last_four');
            }

            if (Schema::hasColumn('users', 'trial_ends_at')) {

                $table->dropColumn('trial_ends_at');
            }

            if (Schema::hasColumn('users', 'primary_tenant_id')) {

                $table->dropColumn('primary_tenant_id');
            }

            if (Schema::hasColumn('users', 'ip_address')) {

                $table->dropColumn('ip_address');
            }

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->dropColumn('user_type');
            $table->string('primary_tenant_id', 30)->nullable();
            $table->string('ip_address', 40)->nullable();
            $table->string('stripe_id', 255)->nullable();
            $table->string('pm_type', 255)->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->string('trial_ends_at', 255)->nullable();
        });
    }
};
