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
        // Create bookings table
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('member_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
            $table->bigInteger('slot_id')->unsigned();
            $table->date('booking_date')->nullable();
            $table->time('booking_start_time')->nullable();
            $table->time('booking_end_time')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->tinyInteger('payment_status')->default(1)->comment('1: PENDING, 2: CONFIRMED');
            $table->tinyInteger('status')->default(1)->comment('1: CONFIRMED, 2: CANCELED');
            $table->timestamps();
            $table->softDeletes();
        });

        // Add primary key and indexes
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('member_id'); // Add index on 'member_id'
            $table->index('service_id'); // Add index on 'service_id'
            $table->index('slot_id'); // Add index on 'slot_id'
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
