<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'photo',
        'payment_frequency',
        'price_of_subscription',
        'subscription_url',
        'subscription_form_url',
        'frequency_title',
        'frequency_description',
        'subscription_package',
        'status' //	1: ACTIVE, 2: INACTIVE
    ];

}
