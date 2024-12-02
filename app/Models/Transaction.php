<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'member_id',
        'transaction_id',
        'subscription_plan_id',
        'transaction_type',
        'invoice_id',
        'amount',
        'payment_method', //0 : Paypal, 1 : Stripe, 2 : Square, 3 : Afterpay
        'status', //	1: COMPLETED, 2: PENDING, 3: CANCELLED
    ];
}
