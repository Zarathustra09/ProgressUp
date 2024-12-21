<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomStaff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            $staff = User::factory()->create([
                'role_id' => 3, // Staff role
                'branch_id' => $room->id,
                'password' => Hash::make('password'),
            ]);

            RoomStaff::create([
                'room_id' => $room->id,
                'staff_id' => $staff->id,
            ]);
        }
    }
}
