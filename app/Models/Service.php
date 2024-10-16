<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'title',
        'photo',
        'description',
        'session_capacity',
        'session_timeframe',
        'blockout_time',
        'status',
        'archived_at',
    ];


    public function category()
    {
        // Define an inverse one-to-many relationship
        return $this->belongsTo(Category::class);
    }
}
