<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create bookings_to_services table
        Schema::create('bookings_to_services', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->integer('service_id');
            $table->integer('service_availability_id');
            $table->timestamp('booking_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('booking_end_time')->default('0000-00-00 00:00:00');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('bookings_to_services', function (Blueprint $table) {
            $table->index('booking_id'); // Add index on 'booking_id'
            $table->index('service_id'); // Add index on 'service_id'
            $table->index('service_availability_id'); // Add index on 'service_availability_id'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings_to_services');
    }
};
