<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\StudentSchedule;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $schedules = StudentSchedule::all();

        foreach ($schedules as $schedule) {
            $attendanceCount = rand(1, $schedule->session); // Random number between 1 and the number of sessions

            Attendance::factory()->count($attendanceCount)->create([
                'schedule_id' => $schedule->id,
                'student_id' => $schedule->student_id,
            ]);
        }
    }
}
