<?php

namespace App\Http\Controllers;

use App\Models\StudentReport;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class StudentReportController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 1)->with('roomStudent.room')->get();
        return view('reports.student.index', compact('users'));
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
        Log::info('Request Data:', $request->all());

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'teacher_id' => 'required|exists:users,id',
            'activities' => 'required|array',
            'remarks' => 'required|string',
            'overall_grade' => 'required|string|in:' . implode(',', array_keys(config('grade'))),
        ]);

        // Check if at least one activity is provided
        $hasActivity = false;
        foreach ($request->activities as $scheduleId => $activitySet) {
            foreach ($activitySet as $activityKey => $activity) {
                if (!empty($activity['descriptions'])) {
                    $hasActivity = true;
                    break 2;
                }
            }
        }

        if (!$hasActivity) {
            return back()->withErrors(['activities' => 'At least one activity is required.'])->withInput();
        }

        $student = User::with('studentSchedules.room')->findOrFail($request->student_id);
        $teacher = User::findOrFail($request->teacher_id);

        $birthdate = $student->birthdate;
        $age = $birthdate->diffInYears(now());

        $reportData = [
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'birthdate' => $birthdate->format('Y-m-d'),
                'age' => $age,
            ],
            'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
            'activities' => [],
            'remarks' => $request->remarks,
            'overall_grade' => config('grade')[$request->overall_grade],
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

        foreach ($request->activities as $scheduleId => $activitySet) {
            foreach ($activitySet as $activityKey => $activity) {
                if (!empty($activity['descriptions'])) {
                    $reportData['activities'][$scheduleId][$activityKey] = [
                        'key' => $activityKey,
                        'descriptions' => $activity['descriptions'],
                    ];
                }
            }
        }

        Log::info('Report Data:', $reportData);

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
        $reports = StudentReport::with('student.studentSchedules.room')->where('student_id', $id)->get();
        return view('reports.student.show', compact('reports'));
    }



    public function print($id)
    {
        $report = StudentReport::with('student.studentSchedules.room')->findOrFail($id);
        $reportData = json_decode($report->report_data, true);
        $pdf = Pdf::loadView('reports.student.print', compact('reportData'));
        return $pdf->download('student_report.pdf');
    }

    public function viewPdf($id)
    {
        $report = StudentReport::with('student.studentSchedules.room')->findOrFail($id);
        $reportData = json_decode($report->report_data, true);
        $pdf = Pdf::loadView('reports.student.print', compact('reportData'));
        return $pdf->stream('student_report.pdf');
    }

    public function destroy($id)
    {
        $report = StudentReport::findOrFail($id);
        $report->delete();

        return response()->json(['success' => 'Report deleted successfully.']);
    }
}
