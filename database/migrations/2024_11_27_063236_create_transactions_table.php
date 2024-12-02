<?php

use Illuminate\Database\Eloquent\SoftDeletingScope;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscription_plan_id')->unsigned()->nullable();
            $table->bigInteger('member_id')->unsigned()->nullable();
            $table->bigInteger('transaction_id')->unsigned()->nullable();
            $table->string('transaction_type')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->double('amount',8,2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->tinyInteger('status')->comment('1: COMPLETED, 2: PENDING, 3: CANCELLED')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Adding indexes to other columns
            $table->index('member_id');
            $table->index('transaction_id');
            $table->index('subscription_plan_id');
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
