<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\StudentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MobileAttendanceController extends Controller
{
    public function show($studentScheduleId)
    {
        $attendances = Attendance::where('schedule_id', $studentScheduleId)->get();

        Log::info('Attendances found for Student Schedule ID:', ['studentScheduleId' => $studentScheduleId, 'attendances' => $attendances]);

        return response()->json($attendances, 200);
    }
}
