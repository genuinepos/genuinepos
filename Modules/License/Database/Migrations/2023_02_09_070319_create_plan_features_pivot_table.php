<?php

use Modules\License\Entities\Feature;
use Modules\License\Entities\Plan;
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
        Schema::create('plan_features', function (Blueprint $table) {
            $table->foreignIdFor(Plan::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Feature::class)->constrained()->onDelete('cascade');
            $table->integer('capacity')->nullable();
            $table->unique(['plan_id', 'feature_id'], 'plan_feature_plan_id_feature_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_features');
    }
};
