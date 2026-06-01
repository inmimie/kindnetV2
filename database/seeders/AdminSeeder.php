<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kindnet.test',
            'phone_number' => '1234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Applicant User',
            'email' => 'ishamimie03@gmail.com',
            'phone_number' => '1234567891',
            'password' => Hash::make('password'),
            'role' => 'applicant',
        ]);
    }
}
