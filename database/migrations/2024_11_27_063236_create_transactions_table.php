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
            $table->integer('member_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('subscription_plan_id')->nullable();
            $table->string('transaction_type')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->double('amount',8,2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->tinyInteger('status')->comment('1: COMPLETED, 2: PENDING, 3: CANCELLED')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
