<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'role_id',
        'phone_number',
        'address',
        'province',
        'birthdate',
        'profile_image',
        'parent_id',
        'email',
        'password',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
    ];

    public function studentMedicalInformation()
    {
        return $this->hasOne(StudentMedicalInformation::class);
    }

    public function studentSchoolDetails()
    {
        return $this->hasOne(StudentSchoolDetails::class);
    }

    public function roomStudent()
    {
        return $this->hasOne(RoomStudent::class, 'student_id');
    }

    public function branch()
    {
        return $this->belongsTo(Room::class, 'branch_id');
    }

    public function roomStaff()
    {
        return $this->hasMany(RoomStaff::class, 'staff_id');
    }
}
