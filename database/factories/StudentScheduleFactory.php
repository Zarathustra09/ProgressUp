<?php

namespace Database\Factories;

use App\Models\StudentSchedule;
use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentScheduleFactory extends Factory
{
    protected $model = StudentSchedule::class;

    public function definition()
    {
        return [
            'student_id' => User::where('role_id', 1)->inRandomOrder()->first()->id,
            'room_id' => Room::inRandomOrder()->first()->id, // Use existing Room ID
            'start_time' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'end_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'event_name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(), // Add this line
            'session' => $this->faker->randomDigit(),
        ];
    }
}
