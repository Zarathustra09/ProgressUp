<?php

namespace App\Http\Controllers;

use App\Models\StudentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceWebController extends Controller
{
    public function show($studentScheduleId)
    {
        $studentSchedule = StudentSchedule::with('attendances')->findOrFail($studentScheduleId);

        Log::info('Student Schedule found:', ['studentSchedule' => $studentSchedule]);

        return view('attendance.show', compact('studentSchedule'));
    }
}
