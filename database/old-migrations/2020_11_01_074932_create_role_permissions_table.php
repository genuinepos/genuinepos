<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->text('user')->nullable();
            $table->text('roles')->nullable();
            $table->text('supplier')->nullable();
            $table->text('customers')->nullable();
            $table->text('product')->nullable();
            $table->text('purchase')->nullable();
            $table->text('s_adjust')->nullable();
            $table->text('sale')->nullable();
            $table->text('register')->nullable();
            $table->text('brand')->nullable();
            $table->text('unit')->nullable();
            $table->text('report')->nullable();
            $table->text('setup')->nullable();
            $table->text('dashboard')->nullable();
            $table->text('accounting')->nullable();
            $table->text('hrms')->nullable();
            $table->text('essential')->nullable();
            $table->text('manufacturing')->nullable();
            $table->text('project')->nullable();
            $table->text('repair')->nullable();
            $table->text('superadmin')->nullable();
            $table->text('e_commerce')->nullable();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
