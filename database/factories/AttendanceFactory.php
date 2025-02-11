<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\StudentSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'student_id' => User::where('role_id', 1)->inRandomOrder()->first()->id,
            'schedule_id' => StudentSchedule::inRandomOrder()->first()->id, // Use existing StudentSchedule ID
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['present', 'absent', 'late']),
        ];
    }
}
