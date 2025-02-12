<?php

namespace App\Http\Controllers;

use App\Models\StudentReport;
use App\Models\User;
use Illuminate\Http\Request;

class StudentReportController extends Controller
{
    public function index()
    {
        $reports = StudentReport::with('student')->get();
        return view('reports.student.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $studentId = $request->query('student_id');
        $student = User::findOrFail($studentId);

        // Get teachers from the same branch with role_id = 3
        $teachers = User::where('branch_id', $student->branch_id)
            ->where('role_id', 3)
            ->get();

        // Get programs from the student's schedules
        $programs = $student->studentSchedules->pluck('event_name')->unique();

        return view('reports.student.create', compact('studentId', 'teachers', 'programs', 'student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::with('studentSchedules.room')->findOrFail($request->student_id);

        $reportData = [
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
            ],
            'schedules' => $student->studentSchedules->map(function ($schedule) {
                return [
                    'event_name' => $schedule->event_name,
                    'description' => $schedule->description,
                    'room' => $schedule->room->name,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                ];
            })->toArray(),
        ];

        $studentReport = StudentReport::create([
            'student_id' => $student->id,
            'report_data' => json_encode($reportData),
        ]);

        return redirect()->route('reports.student.show', $studentReport->id);
    }

    public function show($id)
    {
        $report = StudentReport::with('student.studentSchedules.room')->findOrFail($id);
        return view('reports.student.show', compact('report'));
    }
}
