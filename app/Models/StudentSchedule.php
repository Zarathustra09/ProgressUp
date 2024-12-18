<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'room_id',
        'start_time',
        'end_time',
        'event_name',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id')->where('role_id', 1);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
