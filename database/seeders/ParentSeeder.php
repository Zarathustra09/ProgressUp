<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            User::factory()->create([
                'role_id' => 0, // Parent role
                'branch_id' => $room->id,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
