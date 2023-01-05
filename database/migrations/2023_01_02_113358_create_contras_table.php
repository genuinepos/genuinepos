<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable();
            $table->string('date')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index('contras_branch_id_foreign');
            $table->unsignedBigInteger('receiver_account_id')->nullable()->index('contras_receiver_account_id_foreign');
            $table->unsignedBigInteger('sender_account_id')->nullable()->index('contras_sender_account_id_foreign');
            $table->decimal('amount', 22)->default(0);
            $table->string('attachment')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('contras_user_id_foreign');
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
        Schema::dropIfExists('contras');
    }
}
