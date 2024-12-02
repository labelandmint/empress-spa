<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('business_name', 50)->nullable();
            $table->text('business_address1')->nullable();
            $table->text('business_address2')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('postcode', 50)->nullable();
            $table->string('business_website_address', 50)->nullable();
            $table->bigInteger('business_phone_number')->nullable();
            $table->longText('business_email_address')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->text('company_terms')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('page_background_image', 255)->nullable();
            $table->string('email_logo', 255)->nullable();
            $table->string('email_background_image', 255)->nullable();
            $table->integer('ratio_1')->nullable();
            $table->integer('ratio_2')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->integer('number')->nullable();
            $table->timestamp('ratio_update_time')->nullable();
            $table->string('countdown_timer', 255)->nullable();
            $table->tinyInteger('payment_gateway')->default(1)
                ->comment('0: paypal, 1: stripe, 2: square');
            $table->string('paypal_client_id', 255)->nullable();
            $table->string('paypal_secret_key', 255)->nullable();
            $table->string('stripe_publishable_key', 255)->nullable();
            $table->string('stripe_secret_key', 255)->nullable();
            $table->string('stripe_webhook_secret', 255)->nullable();
            $table->text('successful_registration_subject')->nullable();
            $table->longText('successful_registration_body')->nullable();
            $table->text('payment_failed_subject')->nullable();
            $table->longText('payment_failed_body')->nullable();
            $table->text('successful_payment_subject')->nullable();
            $table->longText('successful_payment_body')->nullable();
            $table->string('square_application_id', 255)->nullable();
            $table->string('square_access_token', 255)->nullable();
            $table->string('square_location_id', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->index('user_id'); // Adds index on 'user_id' for faster lookup
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
