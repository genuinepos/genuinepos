<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_opening_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('supplier_opening_balances_branch_id_foreign');
            $table->unsignedBigInteger('supplier_id')->index('supplier_opening_balances_supplier_id_foreign');
            $table->decimal('amount', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('created_by_id')->index('supplier_opening_balances_created_by_id_foreign');
            $table->boolean('is_show_again')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_opening_balances');
    }
};
