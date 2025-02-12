<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'report_data',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
