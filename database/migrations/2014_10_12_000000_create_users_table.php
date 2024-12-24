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
            $table->string('f_name', 255);
            $table->string('l_name', 255)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('phone_no')->nullable();
            $table->text('photo')->nullable();
            $table->longText('address')->nullable();
            // $table->string('profile_id')->nullable();
            // $table->string('card_id')->nullable();
            $table->tinyInteger('user_role')->comment('1=admin, 2=member, 3=contractor, 4=staff');
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=cancelled, 3=paused');
            $table->integer('rating')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
