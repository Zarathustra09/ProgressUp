<?php

namespace App\Http\Controllers;

use App\Models\StudentSchoolDetails;
use Illuminate\Http\Request;

class StudentSchoolDetailsController extends Controller
{
    public function index()
    {
        return StudentSchoolDetails::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'student_id' => 'required|string|unique:student_school_details,student_id',
            'status' => 'required|in:inactive,active',
        ]);

        return StudentSchoolDetails::create($validated);
    }

    public function show(StudentSchoolDetails $studentSchoolDetails)
    {
        return $studentSchoolDetails;
    }

    public function update(Request $request, StudentSchoolDetails $studentSchoolDetails)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'student_id' => 'required|string|unique:student_school_details,student_id,' . $studentSchoolDetails->id,
            'status' => 'required|in:inactive,active',
        ]);

        $studentSchoolDetails->update($validated);

        return $studentSchoolDetails;
    }

    public function destroy(StudentSchoolDetails $studentSchoolDetails)
    {
        $studentSchoolDetails->delete();

        return response()->noContent();
    }
}
