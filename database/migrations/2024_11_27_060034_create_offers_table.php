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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('offer_name',50);
            $table->integer('service_id');
            $table->timestamp('offer_validity')->nullable();
            $table->tinyInteger('offer_capacity')->nullable();
            $table->timestamp('start_date');
            $table->tinyInteger('status')->comment('1: ACTIVE, 2: INACTIVE');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
