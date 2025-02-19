<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\StudentReport;
use Illuminate\Http\Request;

class MobileReportController extends Controller
{
    public function index($id)
    {
        $reports = StudentReport::where('student_id', $id)->get();
        return response()->json(['reports' => $reports]);
    }

    public function show($id)
    {
        $report = StudentReport::findOrFail($id);
        return response()->json(['report' => $report]);
    }
}
