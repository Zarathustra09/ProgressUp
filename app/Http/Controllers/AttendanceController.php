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
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:student_schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
        ]);

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
