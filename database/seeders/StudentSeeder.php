<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomStudent;
use App\Models\StudentSchoolDetails;
use App\Models\StudentMedicalInformation;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();
        $parents = User::where('role_id', 0)->get(); // Get all parents

        foreach ($rooms as $room) {
            foreach ($parents as $parent) {
                if ($parent->branch_id !== $room->id) {
                    continue;
                }

                for ($i = 0; $i < 3; $i++) {
                    $student = User::factory()->create([
                        'role_id' => 1, // Student role
                        'branch_id' => $room->id,
                        'parent_id' => $parent->id,
                        'password' => Hash::make('password'),
                    ]);

                    $currentYear = date('Y');
                    $randomDigits = mt_rand(100000, 999999);
                    $studentId = "{$currentYear}-{$randomDigits}";

                    StudentSchoolDetails::create([
                        'user_id' => $student->id,
                        'student_id' => $studentId,
                        'status' => 'active',
                    ]);

                    StudentMedicalInformation::create([
                        'user_id' => $student->id,
                        'allergies' => 'None',
                        'notes' => 'No medical conditions',
                        'medication' => 'None',
                    ]);

                    RoomStudent::create([
                        'room_id' => $room->id,
                        'student_id' => $student->id,
                    ]);
                }
            }
        }
    }
}
