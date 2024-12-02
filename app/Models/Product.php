<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'image',
        'title',
        'description',
        'quantity',
        'status', //1: ACTIVE, 2: INACTIVE   
        'archived_at', 
    ];
}
