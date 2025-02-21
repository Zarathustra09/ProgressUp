<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceWebController extends Controller
{
    public function show($studentScheduleId)
    {
        $studentSchedule = StudentSchedule::with('attendances')->findOrFail($studentScheduleId);

        // Convert the date to Asia/Manila timezone
        $studentSchedule->attendances->each(function ($attendance) {
            $attendance->date = $attendance->date->setTimezone('Asia/Manila');
        });

        Log::info('Student Schedule found:', ['studentSchedule' => $studentSchedule]);

        return view('attendance.show', compact('studentSchedule'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:student_schedules,id',
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent,late',
        ]);

        $manilaDate = \Carbon\Carbon::parse($request->date)->setTimezone('Asia/Manila');

        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'schedule_id' => $request->schedule_id,
            'date' => $manilaDate,
            'status' => $request->status,
        ]);

        if ($attendance) {
            return response()->json(['success' => 'Attendance created successfully.'], 200);
        } else {
            return response()->json(['error' => 'Failed to create attendance.'], 500);
        }
    }
}
