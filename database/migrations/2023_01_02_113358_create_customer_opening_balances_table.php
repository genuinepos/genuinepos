<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOpeningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_opening_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('customer_opening_balances_branch_id_foreign');
            $table->unsignedBigInteger('customer_id')->index('customer_opening_balances_customer_id_foreign');
            $table->decimal('amount', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_show_again')->default(true);
            $table->unsignedBigInteger('created_by_id')->index('customer_opening_balances_created_by_id_foreign');
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
        Schema::dropIfExists('customer_opening_balances');
    }
}
