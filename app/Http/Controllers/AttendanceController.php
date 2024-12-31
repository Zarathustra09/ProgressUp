<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentSchedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function show($id)
    {
        $attendance = Attendance::with(['student', 'schedule'])->findOrFail($id);
        return response()->json($attendance);
    }

    public function store(Request $request)
    {
        \Log::info('Attendance request', $request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:student_schedules,id',
            'status' => 'required|in:present,absent,late',
        ]);

        // Check if the user_id matches the student_id
        if ($request->user_id !== $request->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $currentDate = now()->toDateString();

        // Check if an attendance record already exists for the given student_id and today's date
        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->whereDate('created_at', $currentDate)
            ->first();

        if ($existingAttendance) {
            return response()->json(['error' => 'Attendance record already exists for today'], 422);
        }

        $schedule = StudentSchedule::findOrFail($request->schedule_id);
        $currentTime = now();

        if ($currentTime->greaterThan($schedule->start_time)) {
            $request->merge(['status' => 'late']);
        }

        $attendance = Attendance::create($request->all());
        return response()->json($attendance, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:student_schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return response()->json($attendance);
    }
}
