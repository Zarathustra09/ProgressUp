<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $student = User::findOrFail($request->student_id);
        if ((int)$student->parent_id !== (int)$request->user_id) {
            Log::info('Parent ID: ' . $student->parent_id . ' User ID: ' . $request->user_id . ' Student ID: ' . $request->student_id);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $currentDateTime = now()->setTimezone('Asia/Manila');

        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->where('schedule_id', $request->schedule_id)
            ->whereDate('date', $currentDateTime->toDateString())
            ->first();

        if ($existingAttendance) {
            return response()->json(['error' => 'Attendance record already exists for today'], 422);
        }

        $schedule = StudentSchedule::findOrFail($request->schedule_id);
        if ($currentDateTime->greaterThan($schedule->start_time)) {
            $request->merge(['status' => 'late']);
        }

        $request->merge(['date' => $currentDateTime]);

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
