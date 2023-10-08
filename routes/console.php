<?php

use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
use App\Services\Setups\BranchService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;


Artisan::command('dev:init', function () {
    Artisan::call('db:seed --class=TenancyDatabaseSeeder');
});

Artisan::command('dev:m', function () {

    // 1
    // Schema::table('account_ledgers', function (Blueprint $table) {

    //     $table->dropColumn('accounting_voucher_description_id');
    //     $table->unsignedBigInteger('voucher_description_id')->after('loan_payment_id')->nullable();

    //     $table->foreign('voucher_description_id')->references('id')->on('accounting_voucher_descriptions')->onDelete('cascade');
    // });

    Schema::create('processes', function (Blueprint $table) {

        $table->bigIncrements('id');
        $table->unsignedBigInteger('branch_id')->nullable()->index('processes_branch_id_foreign');
        $table->unsignedBigInteger('product_id')->index('processes_product_id_foreign');
        $table->unsignedBigInteger('variant_id')->nullable()->index('processes_variant_id_foreign');
        $table->decimal('total_ingredient_cost', 22, 2)->default(0);
        $table->decimal('wastage_percent', 22, 2)->default(0);
        $table->decimal('wastage_amount', 22, 2)->default(0);
        $table->decimal('total_output_qty', 22, 2)->default(0);
        $table->unsignedBigInteger('unit_id')->nullable()->index('processes_unit_id_foreign');
        $table->decimal('production_cost', 22, 2)->default(0);
        $table->decimal('net_cost', 22, 2)->default(0);
        $table->text('process_instruction')->nullable();
        $table->timestamps();

        $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
        $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
    });

    Schema::create('process_ingredients', function (Blueprint $table) {

        $table->bigIncrements('id');
        $table->unsignedBigInteger('process_id')->index('process_ingredients_process_id_foreign');
        $table->unsignedBigInteger('product_id')->index('process_ingredients_product_id_foreign');
        $table->unsignedBigInteger('variant_id')->nullable()->index('process_ingredients_variant_id_foreign');
        $table->decimal('wastage_percent', 22, 2)->default(0);
        $table->decimal('wastage_amount', 22, 2)->default(0);
        $table->decimal('final_qty', 22, 2)->default(0);
        $table->unsignedBigInteger('unit_id')->nullable()->index('process_ingredients_unit_id_foreign');
        $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
        $table->unsignedBigInteger('tax_ac_id')->nullable();
        $table->tinyInteger('unit_tax_type')->default(1);
        $table->decimal('unit_tax_percent', 22, 2)->default(0);
        $table->decimal('unit_tax_amount', 22, 2)->default(0);
        $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
        $table->decimal('subtotal', 22, 2)->default(0);
        $table->boolean('is_delete_in_update')->default(0);
        $table->timestamps();

        $table->foreign(['process_id'])->references(['id'])->on('processes')->onDelete('CASCADE');
        $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
        $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
        $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
    });
});

Artisan::command('play', function() {
    $notifiable = \App\Models\User::find(3);
    $res =  \URL::temporarySignedRoute(
        'saas.verification.verify',
        Carbon::now()->addMinutes(\Config::get('auth.verification.expire', 60)),
        [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ]
    );
    dd($res);
});