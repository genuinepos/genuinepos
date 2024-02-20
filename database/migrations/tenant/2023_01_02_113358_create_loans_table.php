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
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('loans_branch_id_foreign');
            $table->unsignedBigInteger('purchase_id')->nullable()->index('loans_purchase_id_foreign');
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('loan_company_id')->nullable()->index('loans_loan_company_id_foreign');
            $table->unsignedBigInteger('account_id')->nullable()->index('loans_account_id_foreign');
            $table->unsignedBigInteger('loan_account_id')->nullable()->index('loans_loan_account_id_foreign');
            $table->tinyInteger('type')->nullable();
            $table->decimal('loan_amount', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->decimal('total_paid', 22)->default(0);
            $table->decimal('total_receive', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->text('loan_reason')->nullable();
            $table->string('loan_by', 191)->nullable();
            $table->unsignedBigInteger('created_user_id')->nullable()->index('loans_created_user_id_foreign');
            $table->timestamps();

            $table->foreign(['account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['created_user_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['loan_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['loan_company_id'])->references(['id'])->on('loan_companies')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
