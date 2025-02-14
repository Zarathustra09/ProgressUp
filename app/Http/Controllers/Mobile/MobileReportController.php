<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\StudentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MobileReportController extends Controller
{
    public function showPdf($studentId)
    {
        $reports = StudentReport::with(['student' => function ($query) {
            $query->where('role_id', 1)->with('studentSchedules.room');
        }])->where('student_id', $studentId)->get(['id', 'student_id', 'report_data', 'created_at', 'updated_at']);

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'No reports found for this student.'], 404);
        }

        $reportData = $reports->map(function ($report) {
            return [
                'id' => $report->id,
                'student_id' => $report->student_id,
                'report_data' => json_decode($report->report_data, true),
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
            ];
        });

        return response()->json(['reports' => $reportData], 200);
    }

    public function renderPdf($reportId)
    {
        $report = StudentReport::with(['student' => function ($query) {
            $query->where('role_id', 1)->with('studentSchedules.room');
        }])->findOrFail($reportId);

        $reportData = json_decode($report->report_data, true);

        $pdf = Pdf::loadView('reports.student.print', ['reportData' => $reportData]);

        return $pdf->stream('student_report.pdf');
    }
}
