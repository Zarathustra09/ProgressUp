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
        'branch_id', // Add branch_id to fillable attributes
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

    public function studentRoom()
    {
        return $this->hasMany(RoomStudent::class);
    }

    public function branch()
    {
        return $this->belongsTo(Room::class, 'branch_id');
    }
}
