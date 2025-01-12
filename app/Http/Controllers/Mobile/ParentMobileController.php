<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ParentMobileController extends Controller
{
    public function getChildren($id)
    {
        $students = User::where('parent_id', $id)->with(['branch', 'studentMedicalInformation', 'studentSchoolDetails'])->get();
        return response()->json($students);
    }

    public function getStudentSchedule($studentId)
    {
        $student = User::findOrFail($studentId);
        $schedules = $student->studentSchedules;

        return response()->json($schedules);
    }
}
