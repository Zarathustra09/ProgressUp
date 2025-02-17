<?php

namespace App\Http\Controllers;

use App\Models\AttendanceReport;
use Illuminate\Http\Request;
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
        return response()->json($report);
    }

    public function create()
    {
        return view('attendance_reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'attendance_id' => 'required|exists:attendances,id',
            'date' => 'required|date',
            'text' => 'required|string',
            'attachment' => 'nullable|file',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        AttendanceReport::create($data);

        return response()->json(['success' => 'Attendance report created successfully.'], 201);
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
        $report = AttendanceReport::findOrFail($id);
        $report->delete();

        return redirect()->route('attendance_reports.index')->with('success', 'Attendance report deleted successfully.');
    }

    public function check($attendanceId)
    {

        $exists = AttendanceReport::where('attendance_id', $attendanceId)->exists();
        return response()->json(['exists' => $exists]);
    }
}
