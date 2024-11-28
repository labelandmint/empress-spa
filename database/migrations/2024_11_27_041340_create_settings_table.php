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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('business_name', 255)->nullable();
            $table->text('business_address1')->nullable();
            $table->text('business_address2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postcode', 50);
            $table->string('business_website_address', 100)->nullable();
            $table->bigInteger('business_phone_number');
            $table->longText('business_email_address')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->text('company_terms')->nullable();
            $table->string('logo', 50);
            $table->string('page_background_image', 100)->nullable();
            $table->string('email_logo', 100);
            $table->string('email_background_image', 100)->nullable();
            $table->integer('ratio_1')->nullable();
            $table->integer('ratio_2')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('subtitle', 255);
            $table->integer('number')->nullable();
            $table->string('countdown_timer', 255);
            $table->integer('payment_gateway')->default(0)->comment('0: Paypal, 1: Stripe, 2: Square, 3: Afterpay');
            $table->string('paypal_client_id', 255);
            $table->string('paypal_secret_key', 255);
            $table->string('stripe_publishable_key', 255);
            $table->string('stripe_secret_key', 255);
            $table->string('stripe_webhook_secret', 255);
            $table->string('square_application_id', 255);
            $table->string('square_access_token', 255);
            $table->string('square_location_id', 100);
            $table->text('successful_registration_subject')->nullable();
            $table->longText('successful_registration_body')->nullable();
            $table->text('payment_failed_subject')->nullable();
            $table->longText('payment_failed_body')->nullable();
            $table->text('successful_payment_subject')->nullable();
            $table->longText('successful_payment_body')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
