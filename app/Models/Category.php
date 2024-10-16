<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'status',
    ];

    public function services()
    {
        // Define a relationship where a category can have many services
        return $this->hasMany(Service::class)->where('status', 1);
    }
}

