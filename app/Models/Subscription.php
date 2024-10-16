<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'subscription_start',
        'subscription_end',
        'status',
        'reason_for_pausing',
        'pause_days',
        'pause_date',
        // 'hold_end_date',
    ];

}
