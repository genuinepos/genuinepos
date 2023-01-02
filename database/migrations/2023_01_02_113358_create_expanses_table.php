<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expanses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index('expanses_branch_id_foreign');
            $table->string('attachment')->nullable();
            $table->mediumText('note')->nullable();
            $table->mediumText('category_ids')->nullable();
            $table->decimal('tax_percent', 22)->default(0);
            $table->decimal('tax_amount', 22)->default(0);
            $table->decimal('total_amount', 22)->default(0);
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('paid', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('expense_account_id')->nullable()->index('expanses_expense_account_id_foreign');
            $table->unsignedBigInteger('transfer_branch_to_branch_id')->nullable()->index('expanses_transfer_branch_to_branch_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expanses');
    }
}
