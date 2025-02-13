<?php

namespace App\Http\Controllers;

use App\Models\StudentReport;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'teacher_id' => 'required|exists:users,id',
            'grades' => 'required|array',
            'remarks' => 'required|string',
        ]);

        // Check if at least one criterion is provided
        $hasCriterion = false;
        foreach ($request->grades as $scheduleId => $criteria) {
            foreach ($criteria as $key => $value) {
                if (strpos($key, 'criterion') !== false && strpos($key, 'Grade') === false && !empty($value)) {
                    $hasCriterion = true;
                    break 2;
                }
            }
        }

        if (!$hasCriterion) {
            return back()->withErrors(['grades' => 'At least one criterion is required.'])->withInput();
        }

        $student = User::with('studentSchedules.room')->findOrFail($request->student_id);
        $teacher = User::findOrFail($request->teacher_id);
        $age = \Carbon\Carbon::parse($student->birthdate)->age;

        $reportData = [
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'age' => $age,
            ],
            'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
            'grades' => $request->grades,
            'remarks' => $request->remarks,
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

        StudentReport::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'report_data' => json_encode($reportData),
        ]);

        $pdf = Pdf::loadView('reports.student.print', ['reportData' => $reportData]);

        return $pdf->stream('student_report.pdf');
    }

    public function show($id)
    {
        $report = StudentReport::with('student.studentSchedules.room')->findOrFail($id);
        return view('reports.student.show', compact('report'));
    }



    public function print($id)
    {
        $report = StudentReport::with('student.studentSchedules.room')->findOrFail($id);
        $reportData = json_decode($report->report_data, true);
        $pdf = Pdf::loadView('reports.student.print', compact('reportData'));
        return $pdf->download('student_report.pdf');
    }
}
