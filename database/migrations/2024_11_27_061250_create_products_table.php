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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title',50);
            $table->text('description')->nullable();
            $table->mediumInteger('quantity');
            $table->string('image',150)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: ACTIVE, 2: INACTIVE');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
