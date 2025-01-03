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
        // Create email_templates table
        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_name', 50)->comment('Successful Registration, Payment Failed, Successful Payment');
            $table->string('subject', 255);
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
