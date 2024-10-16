<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'business_name',
        'business_address1',
        'business_address2',
        'city',
        'state',
        'postcode',
        'business_website_address',
        'business_phone_number',
        'business_email_address',
        'company_terms',
        'logo',
        'page_background_image',
        'email_logo',
        'email_background_image',
        'payment_gateway',
        'ratio_1',
        'ratio_2',
        'title',
        'subtitle',
        'number',
        'countdown_timer',
        'payment_gateway',
        'paypal_client_id',
        'paypal_secret_key',
        'stripe_publishable_key',
        'stripe_secret_key',
        'stripe_webhook_secret',
        'successful_registration_subject',
        'successful_registration_body',
        'payment_failed_subject',
        'payment_failed_body',
        'successful_payment_subject',
        'successful_payment_body',
    ];
}
