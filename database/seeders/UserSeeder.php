<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'f_name' => 'Richard',
            'l_name' => 'Owens',
            'email' => 'admin1@yopmail.com',
            'password' => bcrypt('123456'), // For example, use bcrypt or any hashed password
            'phone_no' => 4455331122,
            'photo' => 'https://b-2.in/empress-spa/public/images/1732527287.jpg',
            'address' => 'John Street, Wellington Road, NSW',
            'tag_id' => null,
            'user_role' => 1, // 1 for admin
            'status' => 1, // Active status
            'rating' => null,
            'created_at' => '2024-09-16 10:56:49',
            'updated_at' => '2024-11-25 04:04:47',
            'deleted_at' => null
        ]);
    }
}
