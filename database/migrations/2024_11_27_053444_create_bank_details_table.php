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
        // Create bank_details table with primary key, index, and foreign key
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('card_type', 50)->nullable()->comment('Visa, Mastercard');
            $table->string('card_no', 20)->nullable();
            $table->string('expiration', 7)->nullable()->comment('MM/YYYY format');
            $table->string('cardholder_name', 100)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('security')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add primary key and index
            $table->index('user_id'); // Index on 'user_id'


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
