<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMedicalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'allergies',
        'notes',
        'medication',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
