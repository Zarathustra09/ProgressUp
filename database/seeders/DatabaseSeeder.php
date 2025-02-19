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


        $this->call([
            RoomsTableSeeder::class,
            StaffSeeder::class,
            ParentSeeder::class,
            StudentSeeder::class,
            StudentScheduleSeeder::class,
            AttendanceSeeder::class,
        ]);

        User::create([
            'first_name' => 'Admin',
            'middle_name' => 'Super',
            'last_name' => 'User',
            'role_id' => 2,
            'phone_number' => '1234567890',
            'address' => '123 Admin St',
            'province' => 'Admin Province',
            'birthdate' => '1980-01-01',
            'profile_image' => null,
            'parent_id' => null,
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'branch_id' => 1,
        ]);


    }
}
