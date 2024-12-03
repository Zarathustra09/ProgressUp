<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::create([
             'first_name' => 'Admin',
             'last_name' => 'User',
             'phone_number' => '1234567890',
             'email' => 'test@example.com',
             'password' => Hash::make('password'),
             'role_id' => 2,
         ]);
    }
}
