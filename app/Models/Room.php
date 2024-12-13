<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'location',
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, 'room_student', 'room_id', 'student_id')
            ->where('role_id', 1);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function roomStaff()
    {
        return $this->hasMany(RoomStaff::class, 'room_id');
    }

    public function schedules()
    {
        return $this->hasMany(RoomSchedule::class);
    }
}
