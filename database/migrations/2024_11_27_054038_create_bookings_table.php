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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('service_id');
            $table->integer('slot_id');
            $table->date('booking_date')->nullable();
            $table->time('booking_start_time')->nullable();
            $table->time('booking_end_time')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->tinyInteger('payment_status')->default(1)->comment('1: PENDING, 2: CONFIRMED');
            $table->tinyInteger('status')->default(1)->comment('1: CONFIRMED, 2: CANCELED');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
