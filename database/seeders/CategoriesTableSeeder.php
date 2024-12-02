<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Nail Treatments',
                'status' => 1,
                'created_at' => Carbon::create('2024', '09', '25', '05', '24', '46'),
                'updated_at' => Carbon::create('2024', '09', '25', '05', '55', '31'),
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'Category Manicure',
                'status' => 1,
                'created_at' => Carbon::create('2024', '09', '25', '05', '24', '46'),
                'updated_at' => Carbon::create('2024', '09', '25', '05', '55', '57'),
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'Massage Therapy',
                'status' => 1,
                'created_at' => Carbon::create('2024', '09', '25', '05', '24', '46'),
                'updated_at' => Carbon::create('2024', '09', '25', '05', '56', '15'),
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'Exclusive Hairwash',
                'status' => 1,
                'created_at' => Carbon::create('2024', '09', '25', '05', '24', '46'),
                'updated_at' => Carbon::create('2024', '09', '25', '05', '56', '27'),
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'name' => 'Yoga Studio',
                'status' => 1,
                'created_at' => Carbon::create('2024', '09', '25', '05', '24', '46'),
                'updated_at' => Carbon::create('2024', '09', '25', '05', '56', '27'),
                'deleted_at' => null,
            ],
        ]);
    }
}
