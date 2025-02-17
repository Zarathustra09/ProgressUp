<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceReport;
use Illuminate\Http\Request;

class MobileAttendanceReportController extends Controller
{
    public function show($id)
    {
        $report = AttendanceReport::where('attendance_id', $id)->firstOrFail();
        return response()->json(['report' => $report]);
    }
}
