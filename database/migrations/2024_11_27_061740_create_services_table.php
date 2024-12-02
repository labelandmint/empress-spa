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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->string('title', 50);
            $table->string('photo')->nullable();
            $table->text('description');
            $table->integer('session_capacity');
            $table->integer('session_timeframe');
            $table->tinyInteger('blockout_time');
            $table->timestamp('archived_at')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: ACTIVE, 2: INACTIVE, 3: ARCHIVED');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
