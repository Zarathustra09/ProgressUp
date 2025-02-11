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
            $session = $schedule->session;
            $attendanceCount = min(2, $session); // Ensure attendance does not exceed session

            Attendance::factory()->count($attendanceCount)->create([
                'schedule_id' => $schedule->id,
                'student_id' => $schedule->student_id,
            ]);
        }
    }
}
