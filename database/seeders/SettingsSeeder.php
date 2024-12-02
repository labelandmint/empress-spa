<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'id' => 1,
            'user_id' => 1,
            'business_name' => 'Empress SPA',
            'business_address1' => '226-228 Cooper St.',
            'business_address2' => 'Epping',
            'city' => 'VIC',
            'state' => null,
            'postcode' => '3076',
            'business_website_address' => 'www.empressspa.com.au',
            'business_phone_number' => 1800868888,
            'business_email_address' => 'team@empress.spa',
            'terms_condition' => '<p class="MsoNormal"><b>Empress Spa Subscription App Test...</b></p>', // shortened for brevity
            'company_terms' => '1',
            'logo' => 'https://b-2.in/empress-spa/public/images/1728881009.png',
            'page_background_image' => 'https://b-2.in/empress-spa/public/images/1730000325.png',
            'email_logo' => 'https://b-2.in/empress-spa/public/images/logo.svg',
            'email_background_image' => 'https://b-2.in/empress-spa/public/images/settings-img.png',
            'ratio_1' => 1,
            'ratio_2' => 100,
            'title' => 'Limited Time Offer: Exclusive Empress Spa Package',
            'subtitle' => 'Reserve Your Spot Today and Indulge in Pure Relaxation!',
            'number' => 500,
            'ratio_update_time' => '2024-11-10 22:53:07',
            'countdown_timer' => '04 : 22 : 08 : 25 : 24',
            'payment_gateway' => 2,
            'paypal_client_id' => 'admin1@yopmail.com',
            'paypal_secret_key' => null,
            'stripe_publishable_key' => 'pk_live_51PwxjYP4aPJRT4GwUH2wq2LT...',
            'stripe_secret_key' => 'sk_live_51PwxjYP4aPJRT4Gw36JQZ8Kb...',
            'stripe_webhook_secret' => 'whsec_5qWscLVeUfDNDPfis0ciAEUCx...',
            'successful_registration_subject' => null,
            'successful_registration_body' => 'Yes, we are storing this information because...',
            'payment_failed_subject' => null,
            'payment_failed_body' => null,
            'successful_payment_subject' => null,
            'successful_payment_body' => null,
            'square_application_id' => 'sandbox-sq0idb-8XKX1QtKV0bTiP3KNUEdmw',
            'square_access_token' => 'EAAAl1-w6tp0GufLR3981_y8UuKNga...',
            'square_location_id' => 'L9AMQ68EPDV5R',
            'created_at' => '2024-10-10 11:08:28',
            'updated_at' => '2024-11-10 22:53:07',
            'deleted_at' => null,
        ]);
    }
}
