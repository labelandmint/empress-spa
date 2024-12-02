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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('f_name', 50);
            $table->string('l_name', 50)->nullable();
            $table->string('email', 50);
            $table->string('password', 100);
            $table->bigInteger('phone_no')->nullable();
            $table->text('photo')->nullable();
            $table->longText('address')->nullable();
            $table->string('tag_id', 100)->nullable();
            $table->tinyInteger('user_role')->comment('1=admin, 2=member, 3=contractor, 4=staff');
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=cancelled, 3=paused');
            $table->integer('rating')->nullable();
            $table->timestamps(0); // Automatically handles created_at and updated_at
            $table->softDeletes(); // For soft deletes, adds a deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
