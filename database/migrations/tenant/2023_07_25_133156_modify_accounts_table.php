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
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('account_group_id')->after('id')->nullable();
            $table->string('phone', 255)->after('name')->nullable();
            $table->unsignedBigInteger('contact_id')->after('phone')->nullable();
            $table->text('address')->after('contact_id')->nullable();
            $table->string('bank_branch', 255)->after('bank_id')->nullable();
            $table->text('bank_address')->after('bank_branch')->nullable();
            $table->text('bank_code')->after('bank_address')->nullable();
            $table->text('swift_code')->after('bank_code')->nullable();
            $table->decimal('tax_percent', 22, 2)->after('bank_address')->nullable();
            $table->string('opening_balance_type', 10)->after('opening_balance')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('status')->nullable();
            $table->boolean('is_fixed')->after('created_by_id')->nullable();
            $table->boolean('is_main_capital_account')->after('is_fixed')->nullable();
            $table->boolean('is_main_pl_account')->after('is_main_capital_account')->nullable();
            $table->dropColumn('admin_id');
            $table->dropColumn('debit');
            $table->dropColumn('credit');
            $table->dropColumn('balance');

            $table->foreign('account_group_id')->references('id')->on('account_groups')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['account_group_id']);
            $table->dropColumn('account_group_id');
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('bank_branch');
            $table->dropColumn('bank_address');
            $table->dropColumn('bank_code');
            $table->dropColumn('swift_code');
            $table->dropColumn('tax_percent');
            $table->dropColumn('opening_balance_type');
            $table->dropColumn('is_fixed');
            $table->dropColumn('is_main_capital_account');
            $table->dropColumn('is_main_pl_account');
            $table->dropColumn('is_main_capital_account');
            $table->dropColumn('is_main_capital_account');
            $table->dropColumn('is_main_capital_account');
            $table->unsignedBigInteger('admin_id');
            $table->decimal('debit', 22, 2)->after('opening_balance')->default(0);
            $table->decimal('credit', 22, 2)->after('debit')->default(0);
            $table->decimal('balance', 22, 2)->after('credit')->default(0);
        });
    }
};
