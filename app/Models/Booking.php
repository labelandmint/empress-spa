<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class booking extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'member_id',
        'service_id',
        'slot_id',
        'booking_date',
        'booking_end_time',
        'payment_date',
        'member_rating',
        'payment_status',
        'status',
    ];
}
