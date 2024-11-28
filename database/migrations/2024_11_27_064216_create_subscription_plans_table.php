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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title',50);
            $table->string('description',50);
            $table->string('photo',255)->nullable();
            $table->integer('payment_frequency')->comment('1 : weekly, 2 : Monthly, 3 : Quarterly, 4 : Half-Yearly, 5 : Yearly');
            $table->string('frequency_title',255)->nullable();
            $table->text('frequency_description');
            $table->double('price_of_subscription',8,2);
            $table->string('subscription_url',50);
            $table->string('subscription_form_url',50);
            $table->string('subscription_package',255)->nullable();
            $table->string('subscription_services',255)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: ACTIVE, 2: INACTIVE');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
