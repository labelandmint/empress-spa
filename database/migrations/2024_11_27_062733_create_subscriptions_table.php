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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('subscription_plan_id')->unsigned();
            $table->date('subscription_start');
            $table->date('subscription_end');
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=cancelled, 3=paused');
            $table->text('reason_for_pausing')->nullable();
            $table->integer('pause_days')->nullable();
            $table->timestamp('pause_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
           
            // Adding index to 'user_id' column
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
