<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Add foreign key constraints to the 'bank_details' table
        Schema::table('bank_details', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_user_payment_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Add foreign key constraints to the 'bookings' table
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('member_id', 'bookings_ibfk_1')
                ->references('id')->on('users');
            $table->foreign('service_id', 'bookings_ibfk_3')
                ->references('id')->on('services')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });

        // Add foreign key constraints to the 'offers' table
        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('service_id', 'offers_ibfk_1')
                ->references('id')->on('services');
        });

        // Add foreign key constraints to the 'plans_to_services' table
        Schema::table('plans_to_services', function (Blueprint $table) {
            $table->foreign('product_id', 'plans_to_services_ibfk_1')
                ->references('id')->on('products');
            $table->foreign('service_id', 'plans_to_services_ibfk_2')
                ->references('id')->on('services');
            $table->foreign('subscription_plan_id', 'plans_to_services_ibfk_3')
                ->references('id')->on('subscription_plans');
        });

        // Add foreign key constraints to the 'settings' table
        Schema::table('settings', function (Blueprint $table) {
            $table->foreign('user_id', 'settings_ibfk_1')
                ->references('id')->on('users');
        });

        // Add foreign key constraints to the 'slots' table
        Schema::table('slots', function (Blueprint $table) {
            $table->foreign('service_id', 'fk_service_id')
                ->references('id')->on('services');
        });

        // Add foreign key constraints to the 'subscriptions' table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_user_id')
                ->references('id')->on('users');
        });

        // Add foreign key constraints to the 'transactions' table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('member_id', 'transactions_ibfk_1')
                ->references('id')->on('users');
            $table->foreign('subscription_plan_id', 'transactions_ibfk_2')
                ->references('id')->on('subscription_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop foreign key constraints for 'bank_details'
        Schema::table('bank_details', function (Blueprint $table) {
            $table->dropForeign('fk_user_payment_user_id');
        });

        // Drop foreign key constraints for 'bookings'
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_ibfk_1');
            $table->dropForeign('bookings_ibfk_3');
        });

        // Drop foreign key constraints for 'offers'
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_ibfk_1');
        });

        // Drop foreign key constraints for 'plans_to_services'
        Schema::table('plans_to_services', function (Blueprint $table) {
            $table->dropForeign('plans_to_services_ibfk_1');
            $table->dropForeign('plans_to_services_ibfk_2');
            $table->dropForeign('plans_to_services_ibfk_3');
        });

        // Drop foreign key constraints for 'settings'
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign('settings_ibfk_1');
        });

        // Drop foreign key constraints for 'slots'
        Schema::table('slots', function (Blueprint $table) {
            $table->dropForeign('fk_service_id');
        });

        // Drop foreign key constraints for 'subscriptions'
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('fk_user_id');
        });

        // Drop foreign key constraints for 'transactions'
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_ibfk_1');
            $table->dropForeign('transactions_ibfk_2');
        });
    }
}

