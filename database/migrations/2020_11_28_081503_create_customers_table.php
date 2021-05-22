<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id()->nullable();
            $table->tinyInteger('type')->nullable()->comment('1=customer,2=supplier,3=both');
            $table->string('contact_id')->nullable();
            $table->unsignedBigInteger('customer_group_id')->nullable();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('tax_number')->nullable();
            $table->decimal('opening_balance', 22,2)->default(0.00);
            $table->tinyInteger('pay_term')->nullable()->comment('1=months,2=days');
            $table->integer('pay_term_number')->nullable();
            $table->mediumText('address')->nullable();
            $table->mediumText('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('total_sale', 22, 2)->default(0.00);
            $table->decimal('total_paid', 22, 2)->default(0.00);
            $table->decimal('total_sale_due', 22, 2)->default(0.00);
            $table->decimal('total_sale_return_due', 22, 2)->default(0.00);
            $table->boolean('status')->default(1);
            $table->boolean('is_walk_in_customer')->default(0);
            $table->timestamps();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
