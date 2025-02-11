<?php

namespace Database\Seeders;

use App\Models\StudentSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $students = User::where('role_id', 1)->get();

        foreach ($students as $student) {
            StudentSchedule::factory()->count(2)->create([
                'student_id' => $student->id,
            ]);
        }
    }
}
