<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans_to_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscription_plan_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->timestamps(0);
            $table->softDeletes();
        });
        Schema::table('plans_to_services', function (Blueprint $table) {
            $table->index('service_id');
            $table->index('subscription_plan_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans_to_services');
    }
};
