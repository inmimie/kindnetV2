<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CharityType;

class CharityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CharityType::create(['name' => 'Food Assistance', 'description' => 'Help with groceries and meals.']);
        CharityType::create(['name' => 'Medical Support', 'description' => 'Assistance with medical bills and supplies.']);
        CharityType::create(['name' => 'Education', 'description' => 'Support for school supplies and tuition.']);
    }
}
