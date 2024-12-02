<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'start_time',
        'end_time',
        'capacity',
    ];


    public function category()
    {
        // Define an inverse one-to-many relationship
        return $this->belongsTo(Category::class);
    }
}
