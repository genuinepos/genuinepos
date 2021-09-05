<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('loan_company_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->decimal('loan_amount', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->decimal('total_paid', 22)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->text('loan_reason')->nullable();
            $table->string('loan_by')->nullable();
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->timestamps();
            $table->foreign('loan_company_id')->references('id')->on('loan_companies')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('created_user_id')->references('id')->on('admin_and_users')->onDelete('set null');
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
}
