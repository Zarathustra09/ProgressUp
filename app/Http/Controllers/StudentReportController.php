<?php

namespace App\Http\Controllers;

use App\Models\StudentReport;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentReportController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();

        if ($currentUser->role_id == 3) {
            $users = User::where('role_id', 1)
                ->where('branch_id', $currentUser->branch_id)
                ->with('roomStudent.room')
                ->get();
        } else {
            $users = User::where('role_id', 1)
                ->with('roomStudent.room')
                ->get();
        }

        return view('reports.student.index', compact('users'));
    }

    public function show($id)
    {
        $reports = StudentReport::with('student.studentSchedules.room')->where('student_id', $id)->get();
        return view('reports.student.show', compact('reports'));
    }

    public function showSingle($id)
    {
        $reports = StudentReport::with('student.studentSchedules.room')->where('id', $id)->get();
        return response()->json($reports);
    }

    public function create(Request $request)
    {
        Log::info($request->query('student_id'));
        $studentId = $request->query('student_id');
        $student = User::findOrFail($studentId);

        // Get teachers from the same branch with role_id = 3
        $teachers = User::where('branch_id', $student->branch_id)
            ->where('role_id', 3)
            ->get();

        Log::info($teachers);
        // Get programs from the student's schedules
        $programs = $student->studentSchedules->pluck('event_name', 'id')->unique();

        return view('reports.student.create', compact('studentId', 'teachers', 'programs', 'student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'teacher_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:student_schedules,id',
            'date' => 'required|date',
            'text' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
            }

            $report = StudentReport::create($data);

            DB::commit();

            return redirect()->route('reports.student.show', ['id' => $report->student_id])
                ->with('success', 'Report created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating report: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create report.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'teacher_id' => 'required|exists:users,id',
            'program' => 'required|string',
            'date' => 'required|date',
            'text' => 'required|string',
            'attachment' => 'nullable|file',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $report = StudentReport::findOrFail($id);

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
            }

            $report->update($data);

            DB::commit();

            return response()->json(['success' => 'Report updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating report: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update report.'], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $report = StudentReport::findOrFail($id);
            $report->delete();

            DB::commit();

            return response()->json(['success' => 'Report deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting report: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete report.'], 500);
        }
    }
}
