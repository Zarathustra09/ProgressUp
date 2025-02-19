<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Log\AbstractLogger;

class AttendanceReportController extends Controller
{
    public function index()
    {
        $reports = AttendanceReport::all();
        return view('attendance_reports.index', compact('reports'));
    }

    public function show($id)
    {
        $report = AttendanceReport::where('attendance_id', $id)->firstOrFail();
        $reportData = json_decode($report->report_data, true);

        $pdf = Pdf::loadView('reports.attendance.print', ['reportData' => $reportData]);

        return $pdf->stream('attendance_report.pdf');
    }

    public function create(Request $request)
    {
        Log::info('Create method accessed', ['request' => $request->all()]);

        $studentId = $request->query('student_id');
        $attendanceId = $request->query('attendance_id');
        Log::info('Student ID:', ['student_id' => $studentId]);
        Log::info('Attendance ID:', ['attendance_id' => $attendanceId]);

        $student = User::findOrFail($studentId);
        Log::info('Student found:', ['student' => $student]);

        // Get teachers from the same branch with role_id = 3
        $teachers = User::where('branch_id', $student->branch_id)
            ->where('role_id', 3)
            ->get();
        Log::info('Teachers found:', ['teachers' => $teachers]);

        return view('reports.attendance.create', compact('studentId', 'attendanceId', 'teachers', 'student'));
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
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        DB::beginTransaction();

        try {
            // Check if at least one activity is provided
            $hasActivity = false;
            foreach ($request->activities as $attendanceId => $activitySet) {
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

            // Find the attendance using the ID with the schedule
            $attendance = Attendance::with('schedule')->findOrFail($request->attendance_id);
            $studentSchedule = $attendance->schedule;

            // Find all attendances of the specific student schedule and sort them
            $attendances = Attendance::where('schedule_id', $studentSchedule->id)
                ->where('student_id', $student->id)
                ->orderBy('date')
                ->get();

            // Determine the session number for the current attendance
            $sessionNumber = $attendances->search(function ($att) use ($attendance) {
                    return $att->id === $attendance->id;
                }) + 1;

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
                'schedules' => [
                    [
                        'id' => $studentSchedule->id,
                        'event_name' => $studentSchedule->event_name,
                        'description' => $studentSchedule->description,
                        'session' => $sessionNumber,
                    ]
                ],
            ];

            foreach ($request->activities as $attendanceId => $activitySet) {
                foreach ($activitySet as $activityKey => $activity) {
                    if (!empty($activity['descriptions'])) {
                        $reportData['activities'][$attendanceId][$activityKey] = [
                            'key' => $activityKey,
                            'descriptions' => $activity['descriptions'],
                        ];
                    }
                }
            }

            Log::info('Report Data:', $reportData);

            $attendanceReport = AttendanceReport::create([
                'student_id' => $student->id,
                'attendance_id' => $request->attendance_id,
                'report_data' => json_encode($reportData),
            ]);

            DB::commit();

            return redirect()->route('attendance.show', ['studentScheduleId' => $attendanceReport->attendance->schedule->id])->with('success', 'Attendance report created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating attendance report:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to create attendance report. Please try again.'])->withInput();
        }
    }

    public function edit($id)
    {
        $report = AttendanceReport::where('attendance_id', $id)->firstOrFail();
        return response()->json($report);
    }

    public function update(Request $request, $id)
    {
        Log::info('Request', $request->all());
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'attendance_id' => 'required|exists:attendances,id',
            'date' => 'required|date',
            'text' => 'required|string',
            'attachment' => 'nullable|string',
        ]);

        $report = AttendanceReport::findOrFail($id);
        $report->update($request->all());

        return response()->json(['success' => 'Attendance report updated successfully.'], 200);
    }

    public function destroy($id)
    {
        $report = AttendanceReport::where('attendance_id', $id)->firstOrFail();
        $studentScheduleId = $report->attendance->schedule->id;
        $report->delete();

        return redirect()->route('attendance.show', ['studentScheduleId' => $studentScheduleId])->with('success', 'Attendance report deleted successfully.');
    }

    public function check($attendanceId)
    {
        $exists = AttendanceReport::where('attendance_id', $attendanceId)->exists();
        return response()->json(['exists' => $exists]);
    }
}
