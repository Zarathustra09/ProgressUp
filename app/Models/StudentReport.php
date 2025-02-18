<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id', // Add this line
        'report_data',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->where('role_id', 1);
    }

    public function schedule() // Add this method
    {
        return $this->belongsTo(StudentSchedule::class, 'schedule_id');
    }
}
