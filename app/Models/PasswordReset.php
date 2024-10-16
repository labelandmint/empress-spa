<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    // Specify the table name if it differs from the default convention
    protected $table = 'password_resets';

    // If the table does not have an auto-incrementing id, set this to false
    public $incrementing = false;

    // Specify the primary key if it's not named `id` (e.g., 'token' or another appropriate column)
    protected $primaryKey = ['email', 'token']; // Use an array for composite primary keys

    // If you do not want Eloquent to manage timestamps, set to false
    public $timestamps = true; // Change to false if you do not use timestamps

    // If your table's primary key is a composite key, set the following properties
    protected $keyType = 'string'; // Specify the key type, 'string' if your primary key is a string
}
