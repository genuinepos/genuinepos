<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SAAS\Entities\Payment;
use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('subscriptions', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('tenant_id');
        //     $table->foreignIdFor(User::class)->constrained()->nullOnDelete();
        //     $table->foreignIdFor(Plan::class)->constrained()->nullOnDelete();
        //     $table->foreignIdFor(Payment::class)->nullable()->constrained()->nullOnDelete();
        //     $table->timestamp('start_time');
        //     $table->timestamp('end_time');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
